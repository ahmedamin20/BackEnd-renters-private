FROM python:3.10-slim

WORKDIR /app

RUN apt-get update && apt-get install -y \
    python3-dev \
    build-essential \
    libssl-dev \
    libffi-dev \
    python3-setuptools \
    python3-venv \
    python3-wheel \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN python3.10 -m ensurepip

COPY . /app

RUN pip install --no-cache-dir -r requirements.txt

EXPOSE 5000
CMD ["python3.10", "main.py"]
