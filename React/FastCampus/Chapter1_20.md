[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 20. useReducer 를 사용하여 상태 업데이트 로직 분리하기

## useReducer 이해하기 (20)

우리가 이전에 만든 사용자 리스트 기능에서의 주요 상태 업데이트 로직은 App 컴포넌트 내부에서 이루어졌었습니다. 상태를 업데이트 할 때에는 `useState` 를 사용해서 새로운 상태를 설정해주었는데요, 상태를 관리하게 될 때 useState 를 사용하는것 말고도 다른 방법이 있습니다. 바로, `useReducer` 를 사용하는건데요, 이 Hook 함수를 사용하면 컴포넌트의 상태 업데이트 로직을 컴포넌트에서 분리시킬 수 있습니다. <u>상태 업데이트 로직을 컴포넌트 바깥에 작성 할 수도 있고, 심지어 다른 파일에 작성 후 불러와서 사용 할 수도 있지요.</u>

우선 `reducer` 가 무엇인지 알아보겠습니다. `reducer` 는 현재 상태와 액션 객체를 파라미터로 받아와서 새로운 상태를 반환해주는 함수입니다.

```javascript
function reducer(state, action) {
  // 새로운 상태를 만드는 로직
  // const nextState = ...
  return nextState;
}
```

`reducer` 에서 반환하는 상태는 곧 컴포넌트가 지닐 <u>새로운 상태</u>가 됩니다.

여기서 `action` 은 업데이트를 위한 정보를 가지고 있습니다. 주로 `type` 값을 지닌 객체 형태로 사용하지만, <u>꼭 따라야 할 규칙은 따로 없습니다.</u>

```javascript
// 예시 : ★주로 type 값을 가진다
// 카운터에 1을 더하는 액션
{
  type: 'INCREMENT'
}
// 카운터에 1을 빼는 액션
{
  type: 'DECREMENT'
}
// input 값을 바꾸는 액션
{
  type: 'CHANGE_INPUT',
  key: 'email',
  value: 'tester@react.com'
}
// 새 할 일을 등록하는 액션
{
  type: 'ADD_TODO',
  todo: {
    id: 1,
    text: 'useReducer 배우기',
    done: false,
  }
}
```

보신 것 처럼 `action` 객체의 형태는 자유입니다. type 값을 대문자와 _ 로 구성하는 관습이 존재하기도 하지만, 꼭 따라야 할 필요는 없습니다.

`useReducer` 의 사용법은 다음과 같습니다.

```javascript
const [state, dispatch] = useReducer(reducer, initialState);
// 첫번째 파라미터 : reducer함수
// 두번째 파라미터 : 초기상태
```

`state` 는 우리가 앞으로 컴포넌트에서 사용 할 수 있는 상태를 가르키게 되고, `dispatch` 는 액션을 발생시키는 함수라고 이해하시면 됩니다. 이 함수는 다음과 같이 사용합니다: `dispatch({ type: 'INCREMENT' })`.

```javascript
// Counter.js
function reducer(state, action) {
  switch (action.type) {
    case 'INCREMENT':
      return state + 1;
    case 'DECREMENT':
      return state - 1;
    default:
      return state;
  }
}
```

## App 컴포넌트를 useReducer 로 구현하기 (21)
