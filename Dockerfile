FROM ubuntu:xenial

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends \
        libc-ares-dev \
        ca-certificates \
        build-essential \
        php7.0-dev \
        wget \
        git \
        zlib1g-dev
RUN wget https://github.com/google/protobuf/releases/download/v3.13.0/protobuf-php-3.13.0.tar.gz
RUN tar zxvf protobuf-php-3.13.0.tar.gz
RUN cd protobuf-3.13.0 \
    && ./configure \
    && make \
    && make install \
    && ldconfig
RUN rm -rf protobuf-*
RUN git clone https://github.com/grpc/grpc.git --depth=1
RUN cd grpc \
    && git submodule update --init \
    && make grpc_php_plugin \
    && mv bins/opt/grpc_php_plugin /usr/local/bin/
RUN rm -rf grpc
RUN apt-get clean
RUN rm -r /var/lib/apt/lists/*

#ENTRYPOINT ["protoc", "--plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin"]
