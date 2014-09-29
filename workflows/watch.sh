#!/bin/sh
fswatch  . | xargs -n1 ./go.php
