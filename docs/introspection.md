### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Introspection](docs/introspection.md)

|Command                            |Description                                                                    |Supported  |Tested     |Class/Trait    |Method         |
|---                                |---                                                                            |:-:        |:-:        |---            |---            |
|[isConnected](#isConnected)        |A method to determine if a phpredis object thinks it's connected to a server.  |:white\_check\_mark:        |:white\_check\_mark:        |Introspection  |isConnected    |
|[getHost](#getHost)                |Retrieve our host or unix socket that we're connected to.                      |:white\_check\_mark:        |:white\_check\_mark:        |Introspection  |getHost        |
|[getPort](#getPort)                |Get the port we're connected to.                                               |:white\_check\_mark:        |:white\_check\_mark:        |Introspection  |getPort        |
|[getDbNum](#getDbNum)              |Get the database number phpredis is pointed to.                                |:white\_check\_mark:        |:white\_check\_mark:        |Introspection  |getDbNum       |
|[getTimeout](#getTimeout)          |Get the (write) timeout in use for phpredis.                                   |:white\_check\_mark:        |:white\_check\_mark:        |Introspection  |getTimeout     |
|[getReadTimeout](#getReadTimeout)  |Get the read timeout specified to phpredis or FALSE if we're not connected.    |:white\_check\_mark:        |:white\_check\_mark:        |Introspection  |getReadTimeout |
|[getPersistentID](#getPersistentID)|Gets the persistent ID that phpredis is using.                                 |:white\_check\_mark:        |:white\_check\_mark:        |Introspection  |getPersistentID|
|[getAuth](#getAuth)                |Get the password used to authenticate the phpredis connection.                 |:white\_check\_mark:        |:white\_check\_mark:        |Introspection| getAuth         |
