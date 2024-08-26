FROM mysql:8.1

ENV MYSQL_ROOT_PASSWORD secret
ENV MYSQL_USER aushraful
ENV MYSQL_PASSWORD secret

COPY ./docker/mysql/my.cnf /etc/mysql/conf.d/my.cnf

COPY ./docker/mysql/init.sql /docker-entrypoint-initdb.d/init.sql
