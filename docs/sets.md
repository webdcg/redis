### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Sets](docs/sets.md)

|Command                    |Description                                                    |Supported  |Tested     |Class/Trait    |Method         |
|---                        |---                                                            |:-:        |:-:        |---            |---            |
|[sAdd](#sAdd)              |Add one or more members to a set.                              |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sAdd           |
|[sCard](#sCard)            |Get the number of members in a set.                            |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sCard          |
|[sSize](#sSize)            |Get the number of members in a set.                            |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sSize          |
|[sDiff](#sDiff)            |Subtract multiple sets.                                        |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sDiff          |
|[sDiffStore](#sDiffStore)  |Subtract multiple sets and store the resulting set in a key.   |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sDiffStore     |
|[sInter](#sInter)          |Intersect multiple sets.                                       |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sInter         |
|[sInterStore](#sInterStore)|Intersect multiple sets and store the resulting set in a key.  |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sInterStore    |
|[sIsMember](#sIsMember)    |Determine if a given value is a member of a set.               |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sIsMember      |
|[sContains](#sContains)    |Determine if a given value is a member of a set.               |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sContains      |
|[sMembers](#sMembers)      |Get all the members in a set.                                  |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sMembers       |
|[sGetMembers](#sGetMembers)|Get all the members in a set.                                  |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sGetMembers    |
|[sMove](#sMove)            |Move a member from one set to another.                         |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sMove          |
|[sPop](#sPop)              |Remove and return one or more members of a set at random.      |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sPop           |
|[sRandMember](#sRandMember)|Get one or multiple random members from a set.                 |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sRandMember    |
|[sRem](#sRem)              |Remove one or more members from a set.                         |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sRem           |
|[sRemove](#sRemove)        |Remove one or more members from a set.                         |:white\_check\_mark:        |:white\_check\_mark:        |Sets           |sRemove        |
|[sUnion](#sUnion)          |Add multiple sets.                                             |:x:        |:x:        |Sets           |sUnion         |
|[sUnionStore](#sUnionStore)|Add multiple sets and store the resulting set in a key.        |:x:        |:x:        |Sets           |sUnionStore    |
|[sScan](#sScan)            |Scan a set for members.                                        |:x:        |:x:        |Sets           |sScan          |
