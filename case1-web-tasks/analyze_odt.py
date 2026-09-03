#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Анализ ODT файла (OpenDocument Text).
Использует только стандартную библиотеку Python.
ODT — это ZIP-архив с XML внутри (content.xml — основной текст).
"""

import zipfile
import xml.etree.ElementTree as ET
import sys
import os

# Пространства имён OpenDocument
NS = {
    'office': 'urn:oasis:names:tc:opendocument:xmlns:office:1.0',
    'text': 'urn:oasis:names:tc:opendocument:xmlns:text:1.0',
    'style': 'urn:oasis:names:tc:opendocument:xmlns:style:1.0',
    'table': 'urn:oasis:names:tc:opendocument:xmlns:table:1.0',
    'draw': 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0',
    'dc': 'http://purl.org/dc/elements/1.1/',
    'meta': 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0',
}


def get_text_from_element(elem, ns=NS):
    """Рекурсивно собрать весь текст из элемента и потомков."""
    text = (elem.text or '') + (elem.tail or '')
    for child in elem:
        text += get_text_from_element(child, ns)
    return text


def analyze_odt(path: str) -> dict:
    """
    Анализирует ODT файл и возвращает словарь с метаданными и содержимым.
    """
    path = os.path.abspath(path)
    if not os.path.isfile(path):
        raise FileNotFoundError(f'Файл не найден: {path}')

    if not path.lower().endswith('.odt'):
        raise ValueError('Файл должен иметь расширение .odt')

    result = {
        'path': path,
        'size_bytes': os.path.getsize(path),
        'meta': {},
        'paragraphs': [],
        'full_text': '',
        'files_inside': [],
        'error': None,
    }

    try:
        with zipfile.ZipFile(path, 'r') as zf:
            result['files_inside'] = sorted(zf.namelist())

            # Метаданные из meta.xml
            if 'meta.xml' in zf.namelist():
                meta_xml = zf.read('meta.xml').decode('utf-8', errors='replace')
                root = ET.fromstring(meta_xml)
                for tag in ['creator', 'date', 'description', 'keywords', 'title']:
                    node = root.find(f'.//meta:{tag}', NS)
                    if node is not None and node.text:
                        result['meta'][tag] = node.text.strip()

            # Основной текст из content.xml
            if 'content.xml' not in zf.namelist():
                result['error'] = 'В архиве нет content.xml'
                return result

            content_xml = zf.read('content.xml').decode('utf-8', errors='replace')
            root = ET.fromstring(content_xml)

            body = root.find('.//office:body', NS)
            if body is None:
                body = root

            text_body = body.find('.//office:text', NS) or body
            all_text_parts = []

            for elem in text_body.iter():
                tag = elem.tag
                if tag and '}' in tag:
                    tag = tag.split('}', 1)[1]
                if tag in ('p', 'h', 'list-item'):
                    para_text = get_text_from_element(elem).strip()
                    if para_text:
                        result['paragraphs'].append(para_text)
                        all_text_parts.append(para_text)

            result['full_text'] = '\n'.join(all_text_parts)
            result['char_count'] = len(result['full_text'])
            result['paragraph_count'] = len(result['paragraphs'])

    except zipfile.BadZipFile as e:
        result['error'] = f'Неверный ZIP/ODT: {e}'
    except ET.ParseError as e:
        result['error'] = f'Ошибка разбора XML: {e}'
    except Exception as e:
        result['error'] = str(e)

    return result


def main():
    if len(sys.argv) < 2:
        print('Использование: python analyze_odt.py <путь_к_файлу.odt>')
        print('Пример: python analyze_odt.py document.odt')
        sys.exit(1)

    path = sys.argv[1]
    info = analyze_odt(path)

    print('=' * 60)
    print('АНАЛИЗ ODT ФАЙЛА')
    print('=' * 60)
    print(f"Файл: {info['path']}")
    print(f"Размер: {info['size_bytes']} байт")
    print(f"Файлов в архиве: {len(info['files_inside'])}")
    print('Содержимое архива:', ', '.join(info['files_inside'][:15]))
    if len(info['files_inside']) > 15:
        print('  ... и ещё', len(info['files_inside']) - 15)
    print()

    if info.get('error'):
        print('ОШИБКА:', info['error'])
        sys.exit(2)

    print('Метаданные:')
    for k, v in info.get('meta', {}).items():
        print(f'  {k}: {v}')
    if not info.get('meta'):
        print('  (нет)')
    print()

    print(f"Параграфов: {info.get('paragraph_count', 0)}")
    print(f"Символов текста: {info.get('char_count', 0)}")
    print()
    print('--- Текст (первые 2000 символов) ---')
    print((info.get('full_text') or '')[:2000])
    if len(info.get('full_text', '')) > 2000:
        print('...')
    print('=' * 60)


if __name__ == '__main__':
    main()
