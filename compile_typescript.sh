#!/bin/bash

# Compiles player.ts and outputs player.js in the same directory, then watches for changes
tsc ts/player.ts --outDir js --watch