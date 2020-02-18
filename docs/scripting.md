### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Scripting](docs/scripting.md)

|Command                            |Description                                                                                        |Supported  |Tested     |Class/Trait    |Method         |
|---                                |---                                                                                                |:-:        |:-:        |---            |---            |
|[eval](#eval)                      |Evaluate a LUA script serverside.                                                                  |:white\_check\_mark:        |:white\_check\_mark:        |Scripting      |eval           |
|[evalSha](#evalSha)                |Evaluate a LUA script serverside, from the SHA1 hash of the script instead of the script itself.   |:white\_check\_mark:        |:white\_check\_mark:        |Scripting      |evalSha        |
|[script](#script)                  |Execute the Redis SCRIPT command to perform various operations on the scripting subsystem.         |:white\_check\_mark:        |:white\_check\_mark:        |Scripting      |script         |
|[getLastError](#getLastError)      |The last error message (if any).                                                                   |:white\_check\_mark:        |:white\_check\_mark:        |Scripting      |getLastError   |
|[clearLastError](#clearLastError)  |Clear the last error message.                                                                      |:white\_check\_mark:        |:white\_check\_mark:        |Scripting      |clearLastError |
|[\_prefix](#prefix)                |A utility method to prefix the value with the prefix setting for phpredis.                         |:white\_check\_mark:        |:white\_check\_mark:        |Scripting      |\_prefix       |
|[\_unserialize](#unserialize)      |A utility method to unserialize data with whatever serializer is set up.                           |:white\_check\_mark:        |:white\_check\_mark:        |Scripting      |\_unserialize  |
|[\_serialize](#serialize)          |A utility method to serialize data with whatever serializer is set up.                             |:white\_check\_mark:        |:white\_check\_mark:        |Scripting      |\_serialize    |
