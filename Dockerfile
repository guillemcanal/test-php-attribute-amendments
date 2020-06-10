FROM debian:bullseye-slim

RUN apt-get update && apt-get install -y \
	build-essential \
	autoconf \
	bison \
	re2c \
	libxml2-dev \
	libsqlite3-dev \
	pkg-config \
	git

ARG REPO=php
ARG BRANCH=master
ARG NPROC=

RUN mkdir /php-src && cd /php-src \
	&& git clone --depth=1 \
	--branch=${BRANCH} \
	https://github.com/${REPO}/php-src.git .

RUN cd /php-src \
	&& ./buildconf \
	&& ./configure

RUN cd /php-src && set -ex && make -j${NPROC:-"$(nproc)"}

RUN cd /php-src && make install

