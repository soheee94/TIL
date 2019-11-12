[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 15. 배열에 항목 수정하기

```javascript
const onToggle = id => {
    setUsers(
      users.map(user =>
        // ★불변성 유지
        user.id === id ? { ...user, active: !user.active } : user
      )
    );
  };
```

## 배열 학습 정리
배열에 변화를 줄 때에는 객체와 마찬가지로, <u>불변성</u>을 지켜주어야 합니다. 그렇기 때문에, 배열의 push, splice, sort 등의 함수를 사용하면 안됩니다. 만약에 사용해야 한다면, 기존의 배열을 한번 복사하고 나서 사용해야합니다.
1. 배열 항목 추가 > concat 
2. 배열 항목 삭제 > filter
3. 배열 항목 수정 > map
