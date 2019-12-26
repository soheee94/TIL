# Javascript Naming Rules

[참고 사이트](https://www.robinwieruch.de/javascript-naming-conventions)

## Variables

**camelCase** 사용, 주석 없이도 무엇을 의미하는지 알 수 있도록

```javascript
// bad
var value = "Robin";
// bad
var val = "Robin";
// good
var firstName = "Robin";

// bad
var firstname = "Robin";
// bad
var first_name = "Robin";
// bad
var FIRSTNAME = "Robin";
// bad
var FIRST_NAME = "Robin";
// good
var firstName = "Robin";
```

## Boolean

A prefix like `is`, `are`, or `has` helps every JavaScript developer to distinguish a boolean from another variable

```javascript
// bad
var visible = true;
// good
var isVisible = true;

// bad
var equal = false;
// good
var areEqual = false;

// bad
var encryption = true;
// good
var hasEncryption = true;
```

## Function

**camelCase** 사용, 무엇을 하는 함수인지 접두사 사용해서 표현  
This verb as prefix can be anything (e.g. get, fetch, push, apply, calculate, compute, post).

```javascript
// bad
function name(firstName, lastName) {
  return `${firstName} ${lastName}`;
}
// good
function getName(firstName, lastName) {
  return `${firstName} ${lastName}`;
}
```

## Class

**PascalCase** 사용

```javascript
class SoftwareDeveloper {
  constructor(firstName, lastName) {
    this.firstName = firstName;
    this.lastName = lastName;
  }
}
var me = new SoftwareDeveloper("Robin", "Wieruch");
```

## Component

> React

**PascalCase** 사용

```javascript
// bad
function userProfile(user) {
  return (
    <div>
      <span>First Name: {user.firstName}</span>
      <span>Last Name: {user.lastName}</span>
    </div>
  );
}
// good
function UserProfile(user) {
  return (
    <div>
      <span>First Name: {user.firstName}</span>
      <span>Last Name: {user.lastName}</span>
    </div>
  );
}
```

When a component gets used, it distinguishes itself from native HTML and web components, because its first letter is always written in uppercase.

## Methods

**camelCase** 사용, 함수와 같이 접두사 사용하여 의미하는 바를 나타내 주어야한다.

```javascript
class SoftwareDeveloper {
  constructor(firstName, lastName) {
    this.firstName = firstName;
    this.lastName = lastName;
  }
  getName() {
    return `${this.firstName} ${this.lastName}`;
  }
}
var me = new SoftwareDeveloper("Robin", "Wieruch");
console.log(me.getName());
// "Robin Wieruch"
```

## Private

underscore(\_)를 이름 앞에 붙여준다.

```javascript
class SoftwareDeveloper {
  constructor(firstName, lastName) {
    this.firstName = firstName;
    this.lastName = lastName;
    this.name = _getName(firstName, lastName);
  }
  _getName(firstName, lastName) {
    return `${firstName} ${lastName}`;
  }
}
var me = new SoftwareDeveloper("Robin", "Wieruch");
// good
var name = me.name;
console.log(name);
// "Robin Wieruch"
// bad
name = me._getName(me.firstName, me.lastName);
console.log(name);
// "Robin Wieruch"
```

## Constant

const(상수) **UPPERCASE** 사용

```javascript
var SECONDS = 60;
var MINUTES = 60;
var HOURS = 24;
var DAY = SECONDS * MINUTES * HOURS;

// 한 단어로 표현 안될 경우 밑줄로 구분
var DAYS_UNTIL_TOMORROW = 1;
```

## Files

PascalCase
(e.g. React components)

```
- components/
--- user/
----- UserProfile.js
----- UserList.js
----- UserItem.js
--- ui/
----- Dialog.js
----- Dropdown.js
----- Table.js
```
