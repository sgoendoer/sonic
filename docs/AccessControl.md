# Access Control

In SONIC, access control needs to be implemented by the platform. The SONIC SDK provides an (optional) access control mechanism, which allows for definition of fine granular access control rules. Access permissions defined as AccessControlRuleObjects can be migrated along with the profile.

## Scopes

Access control can be defined per interface (INTERFACE) or per content (CONTENT). While for 

## Base directives

Base directives are set for both scopes and become effective, when no other rule is specified.

## Access Rules

Rules have a directive, an entity to whom they apply, and a target that's being accessed. 

### Syntax

| Attribute | Type | Explanation |
| --------------- | ------------ | ----------- |
| ObjectID | UOID | UOID of the rule object |
| Owner | GlobalID | GlobalID of the owner of the rule |
| Index | Integer | Indicates the importance of the rule. Rules with higher indices overwrite rules with lower ones |
| Directive | \<DENY \| ALLOW> | Specifies whether the rule allows or denies access to the target resource |
| Entity type | \<ALL \| FRIENDS \| GROUP \| INDIVIDUAL> | Type of the entity to which the rule applies |
| Entity ID | \<GlobalID \| UOID \| \*> | ID of the entity to which the rule applies |
| Target type | \<CONTENT \| INTERFACE> | Type of the target of the rule |
| Target | \<UOID \| String> | UOID or name of the target of the rule |
| Comment | String | Textual description for the rule |

### Examples

 * `$ownerGID 0 DENY ALL * INTERFACE *`
 
 > Denies access for everybody for all interfaces
 
 * `$ownerGID 1 ALLOW ALL * INTERFACE person`
 
 > Allows access to the person resource for everybody
 
 * `$ownerGID 2 ALLOW FRIENDS * INTERFACE *`
 
 > Allows access for all friends for all interfaces
 
 * `$ownerGID 3 ALLOW INDIVIDUAL $globalID1 INTERFACE *`
 
 > Allows access to all interfaces for user $globalID1
