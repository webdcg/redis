### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Lists](docs/lists.md)

|Command                    |Description                                                                                |Supported  |Tested     |Class/Trait    |Method         |
|---                        |---                                                                                        |:-:        |:-:        |---            |---            |
|[blPop](#blPop)            |Remove and get the first element in a list                                            |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |blPop          |
|[brPop](#brPop)            |Remove and get the last element in a list                                            |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |brPop          |
|[bRPopLPush](#bRPopLPush)  |Pop a value from a list, push it to another list and return it                             |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |bRPopLPush     |
|[lIndex](#lIndex)          |Get an element from a list by its index                                                    |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lIndex         |
|[lGet](#lGet)              |Get an element from a list by its index                                                    |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lGet           |
|[lInsert](#lInsert)        |Insert an element before or after another element in a list                                |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lInsert        |
|[lLen](#lLen)              |Get the length/size of a list                                                              |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lLen           |
|[lSize](#lSize)            |Get the length/size of a list                                                              |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lSize          |
|[lPop](#lPop)              |Remove and get the first element in a list                                                 |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lPop           |
|[lPush](#lPush)            |Prepend one or multiple values to a list                                                   |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lPush          |
|[lPushx](#lPushx)          |Prepend a value to a list, only if the list exists                                         |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lPushx         |
|[lRange](#lRange)          |Get a range of elements from a list                                                        |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lRange         |
|[lGetRange](#lGetRange)    |Get a range of elements from a list                                                        |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lGetRange      |
|[lRem](#lRem)              |Remove elements from a list                                                                |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lRem           |
|[lRemove](#lRemove)        |Remove elements from a list                                                                |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lRemove        |
|[lSet](#lSet)              |Set the value of an element in a list by its index                                         |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lSet           |
|[lTrim](#lTrim)            |Trim a list to the specified range                                                         |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |lTrim          |
|[listTrim](#listTrim)      |Trim a list to the specified range                                                         |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |listTrim       |
|[rPop](#rPop)              |Remove and get the last element in a list                                                  |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |rPop           |
|[rPopLPush](#rPopLPush)    |Remove the last element in a list, append it to another list and return it (redis >= 1.1)  |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |rPopLPush      |
|[rPush](#rPush)            |Append one or multiple values to a list                                                    |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |rPush          |
|[rPushX](#rPushX)          |Append a value to a list, only if the list exists                                          |:white\_check\_mark:        |:white\_check\_mark:        |Lists          |rPushX         |
