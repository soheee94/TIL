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

 App 컴포넌트에 있던 상태 업데이트 로직들을 `useState` 가 아닌 `useReducer` 를 사용하여 구현해보겠습니다. 우선, App 에서 사용 할 초기 상태를 컴포넌트 바깥으로 분리해주고, App 내부의 로직들을 모두 제거해주세요. 우리가 앞으로 차근차근 구현 할 것입니다.

 ```javascript
  const initialState = {
    inputs:{
      // 생략
    },
    users:[
      // 생략
    ]
  }

  function reducer(state, action) {
    return state;
  }

  function App(){
    const [state, dispatch] = useReducer(reducer, initialState);
    const { users } = state;
    const { username, email } = state.inputs;
  }
 ```

### `onChange` 구현

```javascript
  function reducer(state, action) {
    switch (action.type) {
      case 'CHANGE_INPUT':
        return {
          ...state, //불변성 유지 > state 안에는 inputs와 users 데이터가 들어있다!
          inputs: {
            ...state.inputs, //불변성 유지
            [action.name]: action.value
          }
        };
      default:
        return state;
    }
}

function App(){
  const onChange =  useCallback(e => {
    const { name, value } = e.target;
    dispatch({
      type: 'CHANGE_INPUT',
      name,
      value
    });
  }, []);

```

### `onCreate` 구현

```javascript
function reducer(state, action) {
  switch (action.type) {
    // 생략
    case 'CREATE_USER':
      return {
        inputs: initialState.inputs, // input 값 초기화
        users: state.users.concat(action.user) // 배열 추가는 concat!!
        // ★ state 내 모든 값을 변경하기 때문에 불변성 유지를 위한 spread연산자를 사용하지 않아도 된다
      };
    default:
      return state;
  }
}

function App() {
  const [state, dispatch] = useReducer(reducer, initialState);
  const nextId = useRef(4);

  const { users } = state;
  const { username, email } = state.inputs; // onChange를 통해 변하고 있는 값

  const onCreate = useCallback(() => {
    dispatch({
      type: 'CREATE_USER',
      user: {
        id: nextId.current,
        username,
        email
      }
    });
    nextId.current += 1;
  }, [username, email]);
}
```

### `onToggle`, `onRemove` 구현

```javascript
function reducer(state, action) {
  switch (action.type) {
    // 생략
    case 'TOGGLE_USER':
      return {
        ...state,
        users: state.users.map(user =>
          user.id === action.id 
          ? { ...user, active: !user.active }  // 불변성 유지
          : user
        )
      };
    case 'REMOVE_USER':
      return {
        ...state,
        users: state.users.filter(user => user.id !== action.id)
        // 배열 삭제는 filter!!!
      };
    default:
      return state;
  }
}

function App(){
   const onToggle = useCallback(id => {
    dispatch({
      type: 'TOGGLE_USER',
      id
    });
  }, []);

  const onRemove = useCallback(id => {
    dispatch({
      type: 'REMOVE_USER',
      id
    });
  }, []);
}
```

### 활성 사용자수 구현

```javascript
function countActiveUsers(users) {
  console.log('활성 사용자 수를 세는중...');
  return users.filter(user => user.active).length;
}

const count = useMemo(() => countActiveUsers(users), [users]);
// useMemo : 이전에 계산 한 값을 재사용 (Memorized) 단, users가 변화할 경우에만 실행
```

### useReducer vs useState

컴포넌트에서 관리하는 값이 딱 하나고, 그 값이 단순한 숫자, 문자열 또는 boolean 값이라면 확실히 `useState` 로 관리하는게 편할 것입니다.

하지만, 만약에 컴포넌트에서 관리하는 값이 여러개가 되어서 상태의 구조가 복잡해진다면 `useReducer`로 관리하는 것이 편해질 수도 있습니다.


예를 들어, `setter` 를 한 함수에서 여러번 사용해야 하는 일이 발생한다면
```javascript
setUsers(users => users.concat(user));
setInputs({
  username: '',
  email: ''
});
```
그 때부터 `useReducer` 를 쓸까? 에 대한 고민을 시작합니다. `useReducer` 를 썼을때 편해질 것 같으면 `useReducer` 를 쓰고, 딱히 그럴것같지 않으면 `useState` 를 유지하면 되지요.