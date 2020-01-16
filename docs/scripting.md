### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Scripting](docs/scripting.md)

|Command            |Description                        |Supported  |Tested     |Class/Trait    |Method         |
|---                |---                                |:-:        |:-:        |---            |---            |
|[eval](#eval)|Evaluate a LUA script serverside|:x:|:x:|Scripting|eval|
|[evalSha](#evalSha)|Evaluate a LUA script serverside, from the SHA1 hash of the script instead of the script itself|:x:|:x:|Scripting|evalSha|
|[script](#script)|Execute the Redis SCRIPT command to perform various operations on the scripting subsystem|:x:|:x:|Scripting|script|
|[getLastError](#getLastError)|The last error message (if any)|:x:|:x:|Scripting|getLastError|
|[clearLastError](#clearLastError)|Clear the last error message|:x:|:x:|Scripting|clearLastError|
|[\_prefix](#prefix)|A utility method to prefix the value with the prefix setting for phpredis|:x:|:x:|Scripting|\_prefix|
|[\_unserialize](#unserialize)|A utility method to unserialize data with whatever serializer is set up|:x:|:x:|Scripting|\_unserialize|
|[\_serialize](#serialize)|A utility method to serialize data with whatever serializer is set up|:x:|:x:|Scripting|\_serialize|