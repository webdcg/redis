### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Lists](docs/lists.md)

|Command                    |Description                                                                                |Supported              |Tested                 |Class/Trait    |Method         |
|---                        |---                                                                                        |:-:                    |:-:                    |---            |---            |
|[blPop](#blPop)            |Remove and get the first/last element in a list                                            |:x:                    |:x:                    |Lists          |blPop          |
|[brPop](#brPop)            |Remove and get the first/last element in a list                                            |:x:|:x:|Lists|brPop|
|[bRPopLPush](#bRPopLPush)  |Pop a value from a list, push it to another list and return it                             |:x:|:x:|Lists|bRPopLPush|
lIndex, |[lGet](#lGet)      |Get an element from a list by its index                                                    |:x:|:x:|Lists|lGet|
|[lInsert](#lInsert)        |Insert an element before or after another element in a list                                |:x:|:x:|Lists|lInsert|
|[lLen](#lLen)              |Get the length/size of a list                                                              |:x:|:x:|Lists|lLen|
|[lSize](#lSize)            |Get the length/size of a list                                                              |:x:|:x:|Lists|lSize|
|[lPop](#lPop)              |Remove and get the first element in a list                                                 |:x:|:x:|Lists|lPop|
|[lPush](#lPush)            |Prepend one or multiple values to a list                                                   |:x:|:x:|Lists|lPush|
|[lPushx](#lPushx)          |Prepend a value to a list, only if the list exists                                         |:x:|:x:|Lists|lPushx|
|[lRange](#lRange)          |Get a range of elements from a list                                                        |:x:|:x:|Lists|lRange|
|[lGetRange](#lGetRange)    |Get a range of elements from a list                                                        |:x:|:x:|Lists|lGetRange|
|[lRem](#lRem)              |Remove elements from a list                                                                |:x:|:x:|Lists|lRem|
|[lRemove](#lRemove)        |Remove elements from a list                                                                |:x:|:x:|Lists|lRemove|
|[lSet](#lSet)              |Set the value of an element in a list by its index                                         |:x:|:x:|Lists|lSet|
|[lTrim](#lTrim)            |Trim a list to the specified range                                                         |:x:|:x:|Lists|lTrim|
|[listTrim](#listTrim)      |Trim a list to the specified range                                                         |:x:|:x:|Lists|listTrim|
|[rPop](#rPop)              |Remove and get the last element in a list                                                  |:x:|:x:|Lists|rPop|
|[rPopLPush](#rPopLPush)    |Remove the last element in a list, append it to another list and return it (redis >= 1.1)  |:x:|:x:|Lists|rPopLPush|
|[rPush](#rPush)            |Append one or multiple values to a list                                                    |:x:|:x:|Lists|rPush|
|[rPushX](#rPushX)          |Append a value to a list, only if the list exists                                          |:x:|:x:|Lists|rPushX|