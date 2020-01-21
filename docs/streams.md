### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Streams](docs/streams.md)

|Command                    |Description                                    |Supported  |Tested     |Class/Trait    |Method         |
|---                        |---                                            |:-:        |:-:        |---            |---            |
|[xAck](#xAck)              |Acknowledge one or more pending messages.      |:x:        |:x:        |Streams        |xAck           |
|[xAdd](#xAdd)              |Add a message to a stream.                     |:x:        |:x:        |Streams        |xAdd           |
|[xClaim](#xClaim)          |Acquire ownership of a pending message.        |:x:        |:x:        |Streams        |xClaim         |
|[xDel](#xDel)              |Remove a message from a stream.                |:x:        |:x:        |Streams        |xDel           |
|[xGroup](#xGroup)          |Manage consumer groups.                        |:x:        |:x:        |Streams        |xGroup         |
|[xInfo](#xInfo)            |Get information about a stream.                |:x:        |:x:        |Streams        |xInfo          |
|[xLen](#xLen)              |Get the length of a stream.                    |:x:        |:x:        |Streams        |xLen           |
|[xPending](#xPending)      |Inspect pending messages in a stream.          |:x:        |:x:        |Streams        |xPending       |
|[xRange](#xRange)          |Query a range of messages from a stream.       |:x:        |:x:        |Streams        |xRange         |
|[xRead](#xRead)            |Read message(s) from a stream.                 |:x:        |:x:        |Streams        |xRead          |
|[xReadGroup](#xReadGroup)  |Read stream messages with a group and consumer.|:x:        |:x:        |Streams        |xReadGroup     |
|[xRevRange](#xRevRange)    |Query one or more messages from end to start.  |:x:        |:x:        |Streams        |xRevRange      |
|[xTrim](#xTrim)            |Trim a stream's size.                          |:x:        |:x:        |Streams        |xTrim          |
