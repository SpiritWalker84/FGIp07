import os
from contextlib import asynccontextmanager

import aiomysql
from dotenv import load_dotenv

load_dotenv()

SCHEMA = """
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    telegram_id BIGINT UNIQUE NOT NULL,
    username VARCHAR(255),
    full_name VARCHAR(255),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    description TEXT,
    status ENUM('new', 'in_progress', 'done') DEFAULT 'new',
    creator_id INT NOT NULL,
    assignee_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (creator_id) REFERENCES users(id),
    FOREIGN KEY (assignee_id) REFERENCES users(id)
);
"""


async def create_pool():
    return await aiomysql.create_pool(
        host=os.getenv("MYSQL_HOST", "mysql"),
        port=int(os.getenv("MYSQL_PORT", "3306")),
        user=os.getenv("MYSQL_USER", "taskbot"),
        password=os.getenv("MYSQL_PASSWORD", "secret"),
        db=os.getenv("MYSQL_DATABASE", "taskbot"),
        autocommit=True,
        minsize=1,
        maxsize=5,
    )


async def init_db(pool):
    async with pool.acquire() as conn:
        async with conn.cursor() as cur:
            for stmt in SCHEMA.strip().split(";"):
                stmt = stmt.strip()
                if stmt:
                    await cur.execute(stmt)


@asynccontextmanager
async def get_conn(pool):
    async with pool.acquire() as conn:
        async with conn.cursor(aiomysql.DictCursor) as cur:
            yield conn, cur


async def get_or_create_user(pool, telegram_id: int, username: str | None, full_name: str):
    admin_id = os.getenv("ADMIN_TELEGRAM_ID", "").strip()
    async with get_conn(pool) as (_, cur):
        await cur.execute("SELECT * FROM users WHERE telegram_id = %s", (telegram_id,))
        row = await cur.fetchone()
        if row:
            return row
        role = "admin" if admin_id and str(telegram_id) == admin_id else "user"
        await cur.execute(
            "INSERT INTO users (telegram_id, username, full_name, role) VALUES (%s, %s, %s, %s)",
            (telegram_id, username, full_name, role),
        )
        await cur.execute("SELECT * FROM users WHERE telegram_id = %s", (telegram_id,))
        return await cur.fetchone()


async def list_tasks(pool, user_id: int, is_admin: bool):
    async with get_conn(pool) as (_, cur):
        if is_admin:
            await cur.execute(
                """
                SELECT t.id, t.title, t.status, u.full_name AS assignee
                FROM tasks t
                LEFT JOIN users u ON u.id = t.assignee_id
                ORDER BY t.id DESC LIMIT 20
                """
            )
        else:
            await cur.execute(
                """
                SELECT t.id, t.title, t.status, u.full_name AS assignee
                FROM tasks t
                LEFT JOIN users u ON u.id = t.assignee_id
                WHERE t.creator_id = %s OR t.assignee_id = %s
                ORDER BY t.id DESC LIMIT 20
                """,
                (user_id, user_id),
            )
        return await cur.fetchall()


async def create_task(pool, creator_id: int, title: str, description: str | None, assignee_id: int | None):
    async with get_conn(pool) as (_, cur):
        await cur.execute(
            "INSERT INTO tasks (title, description, creator_id, assignee_id) VALUES (%s, %s, %s, %s)",
            (title, description, creator_id, assignee_id),
        )
        return cur.lastrowid


async def mark_done(pool, task_id: int, user_id: int, is_admin: bool) -> bool:
    async with get_conn(pool) as (_, cur):
        if is_admin:
            await cur.execute("UPDATE tasks SET status = 'done' WHERE id = %s", (task_id,))
        else:
            await cur.execute(
                """
                UPDATE tasks SET status = 'done'
                WHERE id = %s AND (creator_id = %s OR assignee_id = %s)
                """,
                (task_id, user_id, user_id),
            )
        return cur.rowcount > 0


async def list_users(pool):
    async with get_conn(pool) as (_, cur):
        await cur.execute("SELECT id, full_name FROM users ORDER BY id")
        return await cur.fetchall()
