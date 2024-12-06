import random
import argparse
import requests
from concurrent.futures import ThreadPoolExecutor

# Функция для загрузки параметров из файла
def load_params_from_file(input_file="params.txt"):
    try:
        with open(input_file, "r", encoding="utf-8") as file:
            params = [line.strip() for line in file.readlines()]
        return params
    except Exception as e:
        print(f"Ошибка при чтении файла: {e}")
        return []

# Список доменов
domains = ['ca', 'uk', 'fr', 'au', 'pl', 'it', 'nl']

# Генерация случайных строк с параметрами
def generate_params(num_params=150, input_file="params.txt", output_file="generated_params.txt"):
    # Загружаем параметры из файла
    params = load_params_from_file(input_file)

    # Если параметры пустые, сообщаем об ошибке
    if not params:
        print("Нет доступных параметров для генерации.")
        return

    # Список для хранения сгенерированных строк
    generated_strings = set()

    # Генерация параметров
    while len(generated_strings) < num_params:
        domain = random.choice(domains)
        param = random.choice(params)
        generated_strings.add(f'site:.{domain} inurl:".php?{param}="')

    # Сохранение сгенерированных строк в файл
    try:
        with open(output_file, "w", encoding="utf-8") as file:
            file.write("\n".join(generated_strings))
        print(f"{len(generated_strings)} параметров сохранено в файл {output_file}")
    except Exception as e:
        print(f"Ошибка при сохранении файла: {e}")

# Функция для загрузки доров из файла
def load_dorks(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        return [line.strip() for line in f if line.strip()]

# Функция для сохранения уникальных URL-адресов в файл
def save_unique_urls(file_path, urls):
    # Загрузить существующие URL, если файл уже есть
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            existing_urls = set(line.strip() for line in f if line.strip())
    except FileNotFoundError:
        existing_urls = set()
    
    # Добавить новые URL без дубликатов
    unique_urls = existing_urls.union(urls)
    
    # Записать уникальные URL обратно в файл
    with open(file_path, 'w', encoding='utf-8') as f:
        for url in sorted(unique_urls):  # Можно отсортировать, если нужно
            f.write(url + '\n')

# Функция для выполнения запроса и извлечения URL-адресов
def fetch_urls(dork, auth, limit=100):
    payload = {
        'source': 'google_search',
        'query': dork,
        'limit': str(limit),
        'pages': '1',
        'start_page': '1',
        'parse': True,
        'user_agent_type': 'desktop',
        'context': [
            {'key': 'filter', 'value': 1},
            {'key': 'nfpr', 'value': True}
        ],
    }
    try:
        response = requests.post(
            'https://realtime.oxylabs.io/v1/queries',
            auth=auth,
            json=payload,
            timeout=30  # Установка таймаута
        )
        response.raise_for_status()
        data = response.json()
    except Exception as e:
        print(f"Error fetching URLs for dork '{dork}': {e}")
        return set()

    urls = set()
    if "results" in data:
        for result in data["results"]:
            content = result.get('content', {})
            if isinstance(content, dict):
                organic_results = content.get('results', {}).get('organic', [])
                for org in organic_results:
                    url = org.get('url')
                    if url and url.startswith('https') and '.php?' in url:
                        urls.add(url)
    return urls

# Основная функция для обработки доров с многопоточностью
def main():
    # Парсинг аргументов командной строки
    parser = argparse.ArgumentParser(description="Генерация параметров и обработка доров.")
    parser.add_argument("-n", "--num", type=int, default=150, help="Количество генерируемых строк (по умолчанию 150).")
    parser.add_argument("-o", "--output", type=str, default="generated_params.txt", help="Файл для сохранения сгенерированных строк.")
    args = parser.parse_args()

    # Генерация параметров с учётом аргументов командной строки
    generate_params(num_params=args.num, output_file=args.output)

    dork_file = 'dd.txt'  # Файл с дорами
    output_file = 'filtered_urls.txt'  # Файл для сохранения URL
    auth = ('Stark_K7GfF', 'StarkPass03Q+')  # Учетные данные для API
    
    # Загрузить доры
    dorks = load_dorks(dork_file)
    
    # Использовать ThreadPoolExecutor для параллельной обработки
    all_urls = set()
    with ThreadPoolExecutor(max_workers=10) as executor:  # Количество потоков
        futures = {executor.submit(fetch_urls, dork, auth): dork for dork in dorks}
        for future in futures:
            dork = futures[future]
            try:
                urls = future.result()
                all_urls.update(urls)
                print(f"Processed dork: {dork}, found {len(urls)} URLs")
            except Exception as e:
                print(f"Error processing dork '{dork}': {e}")
    
    # Сохранить результаты в файл без дубликатов
    save_unique_urls(output_file, all_urls)
    print(f"Saved {len(all_urls)} unique URLs to {output_file}")

# Запуск основной функции
if __name__ == '__main__':
    main()