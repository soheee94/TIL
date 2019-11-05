[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 13. 배열에 항목 추가하기

배열에 변화를 줄 때에는 객체와 마찬가지로, <u>불변성</u>을 지켜주어야 합니다. 그렇기 때문에, 배열의 push, splice, sort 등의 함수를 사용하면 안됩니다. 만약에 사용해야 한다면, 기존의 배열을 한번 복사하고 나서 사용해야합니다.

불변성을 지키면서 배열에 새 항목을 추가하는 방법은 두가지가 있습니다.

첫번째는 **spread 연산자**를 사용하는 것 입니다.

```javascript
setUsers([...users, user]);
```

또 다른 방법은 `concat` 함수를 사용하는 것 입니다. `concat` 함수는 기존의 배열을 수정하지 않고, 새로운 원소가 추가된 새로운 배열을 만들어줍니다.
```javascript
setUsers(users.concat(user));
```

> 불변성을 지켜주어야만 리액트 컴포넌트에서 상태가 업데이트 됬음을 감지할 수 있고 이에 따라 필요한 리렌더링이 진행된다. [Chapter1_09](https://github.com/soheee94/TIL/blob/master/React/FastCampus/Chapter1_07~09.md)
