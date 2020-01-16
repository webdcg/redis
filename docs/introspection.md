### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Introspection](docs/introspection.md)

|Command                            |Description                                                                    |Supported  |Tested     |Class/Trait    |Method         |
|---                                |---                                                                            |:-:        |:-:        |---            |---            |
|[isConnected](#isConnected)        |A method to determine if a phpredis object thinks it's connected to a server.  |:x:        |:x:        |Introspection  |isConnected    |
|[getHost](#getHost)                |Retrieve our host or unix socket that we're connected to.                      |:x:        |:x:        |Introspection  |getHost        |
|[getPort](#getPort)                |Get the port we're connected to.                                               |:x:        |:x:        |Introspection  |getPort        |
|[getDbNum](#getDbNum)              |Get the database number phpredis is pointed to.                                |:x:        |:x:        |Introspection  |getDbNum       |
|[getTimeout](#getTimeout)          |Get the (write) timeout in use for phpredis.                                   |:x:        |:x:        |Introspection  |getTimeout     |
|[getReadTimeout](#getReadTimeout)  |Get the read timeout specified to phpredis or FALSE if we're not connected.    |:x:        |:x:        |Introspection  |getReadTimeout |
|[getPersistentID](#getPersistentID)|Gets the persistent ID that phpredis is using.                                 |:x:        |:x:        |Introspection  |getPersistentID|
|[getAuth](#getAuth)                |Get the password used to authenticate the phpredis connection.                 |:x:        |:x:        |Introspection| getAuth         |
