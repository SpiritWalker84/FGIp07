import asyncio
import logging
import os
import sys

from aiogram import Bot, Dispatcher, F
from aiogram.filters import Command, CommandStart
from aiogram.fsm.context import FSMContext
from aiogram.fsm.state import State, StatesGroup
from aiogram.fsm.storage.memory import MemoryStorage
from aiogram.types import Message
from dotenv import load_dotenv

from bot import db

load_dotenv()
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

STATUS_LABELS = {"new": "Новая", "in_progress": "В работе", "done": "Готово"}


class NewTask(StatesGroup):
    title = State()
    description = State()


def help_text() -> str:
    return (
        "Task Manager Bot — MVP по ТЗ заказа FL.ru\n\n"
        "/tasks — список задач\n"
        "/new — создать задачу\n"
        "/done <id> — отметить выполненной\n"
        "/help — эта справка"
    )


async def cmd_start(message: Message, pool):
    user = await db.get_or_create_user(
        pool,
        message.from_user.id,
        message.from_user.username,
        message.from_user.full_name or "User",
    )
    role = "администратор" if user["role"] == "admin" else "участник"
    await message.answer(f"Привет, {user['full_name']}!\nРоль: {role}.\n\n{help_text()}")


async def cmd_help(message: Message, pool):
    await message.answer(help_text())


async def cmd_tasks(message: Message, pool):
    user = await db.get_or_create_user(
        pool,
        message.from_user.id,
        message.from_user.username,
        message.from_user.full_name or "User",
    )
    tasks = await db.list_tasks(pool, user["id"], user["role"] == "admin")
    if not tasks:
        await message.answer("Задач пока нет. Создайте: /new")
        return
    lines = ["📋 Ваши задачи:\n"]
    for t in tasks:
        st = STATUS_LABELS.get(t["status"], t["status"])
        assignee = t["assignee"] or "—"
        lines.append(f"#{t['id']} {t['title']}\n  {st} · {assignee}")
    await message.answer("\n".join(lines))


async def cmd_new(message: Message, state: FSMContext):
    await state.set_state(NewTask.title)
    await message.answer("Введите название задачи:")


async def on_title(message: Message, state: FSMContext):
    await state.update_data(title=message.text.strip())
    await state.set_state(NewTask.description)
    await message.answer("Описание (или «-» чтобы пропустить):")


async def on_description(message: Message, state: FSMContext, pool):
    data = await state.get_data()
    desc = None if message.text.strip() == "-" else message.text.strip()
    user = await db.get_or_create_user(
        pool,
        message.from_user.id,
        message.from_user.username,
        message.from_user.full_name or "User",
    )
    task_id = await db.create_task(pool, user["id"], data["title"], desc, user["id"])
    await state.clear()
    await message.answer(f"✅ Задача #{task_id} создана: {data['title']}")


async def cmd_done(message: Message, pool):
    parts = message.text.split(maxsplit=1)
    if len(parts) < 2 or not parts[1].isdigit():
        await message.answer("Использование: /done <id>")
        return
    task_id = int(parts[1])
    user = await db.get_or_create_user(
        pool,
        message.from_user.id,
        message.from_user.username,
        message.from_user.full_name or "User",
    )
    ok = await db.mark_done(pool, task_id, user["id"], user["role"] == "admin")
    if ok:
        await message.answer(f"Задача #{task_id} отмечена выполненной.")
    else:
        await message.answer("Задача не найдена или нет прав.")


async def main():
    token = os.getenv("BOT_TOKEN", "").strip()
    if not token:
        logger.error("Set BOT_TOKEN in .env")
        sys.exit(1)

    pool = await db.create_pool()
    await db.init_db(pool)

    bot = Bot(token=token)
    dp = Dispatcher(storage=MemoryStorage())

    @dp.message(CommandStart())
    async def _start(m: Message):
        await cmd_start(m, pool)

    @dp.message(Command("help"))
    async def _help(m: Message):
        await cmd_help(m, pool)

    @dp.message(Command("tasks"))
    async def _tasks(m: Message):
        await cmd_tasks(m, pool)

    @dp.message(Command("new"))
    async def _new(m: Message, state: FSMContext):
        await cmd_new(m, state)

    @dp.message(NewTask.title)
    async def _title(m: Message, state: FSMContext):
        await on_title(m, state)

    @dp.message(NewTask.description)
    async def _desc(m: Message, state: FSMContext):
        await on_description(m, state, pool)

    @dp.message(Command("done"))
    async def _done(m: Message):
        await cmd_done(m, pool)

    logger.info("Bot started")
    await dp.start_polling(bot)


if __name__ == "__main__":
    asyncio.run(main())
