# Dockerfile for Nmap

# Use a lightweight base image
FROM python:3.11-slim

# Set environment variables
ENV PYTHONDONTWRITEBYTECODE 1
ENV PYTHONUNBUFFERED 1

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
	nmap \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

# Copy requirements file and install Python dependencies
COPY ./scanning/network-scanning/nmap/requirements.txt /app/
RUN pip install --no-cache-dir -r requirements.txt

# Copy the Python script to the container
COPY ./scanning/network-scanning/nmap/run_nmap.py /app/

# Expose port 5000 for the Flask application
EXPOSE 5000

# Command to run the Flask application
CMD ["python", "run_nmap.py"]
