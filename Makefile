PROTO_FILE := ${PWD}/service.proto
PROTO_DIR := ${PWD}
PHP_OUT := ${PWD}/src/pb

#gen:
#	rm -Rf ${PROTO_FILE} ${PHP_OUT} && \
#	wget -O ${PROTO_FILE} https://raw.githubusercontent.com/chaseisabelle/phprom/master/api/proto/v1/service.proto && \
#	docker pull jaegertracing/protobuf && \
#	docker run --rm \
#		-v${PROTO_DIR}:${PROTO_DIR} \
#		-v${PHP_OUT}:${PHP_OUT} \
#		-w${PHP_OUT} \
#		jaegertracing/protobuf:latest \
#			--proto_path=${PROTO_DIR} \
#			--php_out=plugins=grpc:${PHP_OUT} \
#			-I/usr/include/github.com/gogo/protobuf \
#			${PROTO_DIR}/service.proto && \
#    rm -f ${PROTO_FILE}

gen:
	rm -Rf ${PROTO_FILE} ${PHP_OUT} && \
	wget -O ${PROTO_FILE} https://raw.githubusercontent.com/chaseisabelle/phprom/master/api/proto/v1/service.proto && \
	docker run --rm \
		-v${PROTO_DIR}:${PROTO_DIR} \
		-v${PHP_OUT}:${PHP_OUT} \
		-w${PHP_OUT} \
		chaseisabelle/protoc-gen-php:latest \
			protoc \
			--proto_path=${PROTO_DIR} \
			--php_out=${PHP_OUT}   \
			--grpc_out=${PHP_OUT}  \
			--plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin  \
			-I / \
			${PROTO_FILE} && \
    rm -f ${PROTO_FILE}
