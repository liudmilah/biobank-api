FROM centrifugo/centrifugo

RUN apk add --no-cache curl

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s CMD curl --fail http://127.0.0.1:8000/health || exit 1