#!/bin/bash

for line in $(ls $1);do
	svn commit -m "$2" $line
done
