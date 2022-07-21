FROM postgres:13.3-alpine

COPY ./common/postgres/load-extensions.sh /docker-entrypoint-initdb.d/
RUN chmod 755 /docker-entrypoint-initdb.d/load-extensions.sh