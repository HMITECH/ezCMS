# help:: ; @perl -lne 'print if s/^([\S]+:+).*/$$1/'   Makefile

help:: ; @grep -Pi "^[\w-]+:" Makefile
