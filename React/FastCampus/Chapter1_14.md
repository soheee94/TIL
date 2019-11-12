[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 14. 배열에 항목 제거하기

```javascript
 // UserList.js
 // onRemove(user.id) 로 바로 호출은 X!!
 <button onClick={() => onRemove(user.id)}>삭제</button>
```

배열에 있는 항목을 제거할 때에는, 추가할떄와 마찬가지로 **불변성**을 지켜가면서 업데이트를 해주어야 합니다.

불변성을 지키면서 특정 원소를 배열에서 제거하기 위해서는 `filter` 배열 내장 함수를 사용하는것이 가장 편합니다. 이 함수는 배열에서 특정 조건이 만족하는 원소들만 추출하여 새로운 배열을 만들어줍니다. [(참고)](https://learnjs.vlpt.us/basics/09-array-functions.html#filter)

```javascript
// App.js
const onRemove = id => {
    // user.id 가 파라미터로 일치하지 않는 원소만 추출해서 새로운 배열을 만듬
    // = user.id 가 id 인 것을 제거함
    // => filter 불변성을 지킴
    setUsers(users.filter(user => user.id !== id));
};
```