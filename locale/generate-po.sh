#!/bin/sh
xgettext -F --from-code=utf-8 -L PHP --no-location -j "./fr_BE.UTF-8/LC_MESSAGES/members.po" -d members -p "./fr_BE.UTF-8/LC_MESSAGES" ../public/*.php
xgettext -F --from-code=utf-8 -L PHP --no-location -j "./nl_BE.UTF-8/LC_MESSAGES/members.po" -d members -p "./nl_BE.UTF-8/LC_MESSAGES" ../public/*.php
