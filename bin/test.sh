#!/bin/bash
# Run only from application context using bin/test.sh

docker-compose -f docker-compose-test.yaml -p "fscs_executor_tests_$RANDOM$RANDOM" up \
    --build \
    --abort-on-container-exit \
    --exit-code-from=fscs-executor
