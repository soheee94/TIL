[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 25~26. Immer 를 사용한 더 쉬운 불변성 관리

리액트에서 배열이나 객체를 업데이트 해야 할 때에는 직접 수정 하면 안되고 <mark>불변성을 지켜주면서 업데이트</mark>를 해주어야 합니다.

예를 들자면 다음과 같이 하면 안되고

```javascript
const object = {
  a: 1,
  b: 2
};

object.b = 3;
```
다음과 같이 `...` 연산자를 사용해서 새로운 객체를 만들어주어야 한다.

```javascript
const object = {
  a: 1,
  b: 2
};

const nextObject = {
  ...object,
  b: 3
};
```

배열도 마찬가지로 `push`, `splice` 등의 함수를 사용하거나 n 번째 항목을 직접 수정하면 안되고 다음과 같이 `concat`, `filter`, `map` 등의 함수를 사용해야 합니다.

데이터의 구조가 조금 까다로워지면 불변성을 지켜가면서 새로운 데이터를 생성해내는 코드가 조금 복잡해집니다.

 `Immer` 를 사용하면 우리가 상태를 업데이트 할 때, <u>불변성을 신경쓰지 않아도 Immer 가 불변성 관리를 대신 해줍니다.</u>

 ## Immer 사용법
우선 프로젝트에서 다음 명령어를 실행하여 Immer 를 설치해주세요.

```
$ yarn add immer
```

우선 코드의 상단에서 `immer` 를 불러와주어야 합니다. 보통 `produce` 라는 이름으로 불러옵니다.

```javascript
import produce from 'immer';
```
그리고 produce 함수를 사용 할 때에는   
1. 첫번째 파라미터에는 수정하고 싶은 상태,   
2. 두번째 파라미터에는 어떻게 업데이트하고 싶을지 정의하는 함수를 넣어줍니다.

두번째 파라미터에 넣는 함수에서는 불변성에 대해서 신경쓰지 않고 그냥 업데이트 해주면 다 알아서 해줍니다.

```javascript
const state = {
  number: 1,
  dontChangeMe: 2
};

const nextState = produce(state, draft => {
  // ★ ★ state가 아닌 draft로 작성
  draft.number += 1;
});

console.log(nextState);
// { number: 2, dontChangeMe: 2 }
```

## 리듀서에서 Immer 사용하기

> Immer 를 사용해서 간단해지는 업데이트가 있고, 오히려 코드가 길어지는 업데이트들이 있습니다.

```javascript
function reducer(state, action){
  switch(action.type){
    case 'CREATE_USER':
      return produce(state, draft => {
        draft.users.push(action.user)
      })
      // 이런 형태는 굳이 사용하지 않아도 되고
      // return{
      //   // inputs : initialState.inputs,
      //   users: state.users.concat(action.user)
      // }
    case 'TOGGLE_USER':
      return produce(state, draft => {
        const user = draft.users.find(user => user.id === action.id);
        user.active = !user.active;
      })
      // 이렇게 코드 구조가 복잡할 경우는 IMMER 를 사용하는 것이 좋다
      // 훨씬 깔끔해지기 때문에!
      // return{
      //   ...state,
      //   users: state.users.map(user =>
      //     user.id === action.id
      //     ? {...user, active: !user.active}
      //     : user
      //   )
      // }
    case 'REMOVE_USER':
      // 이런 형태도 굳이 사용하지 않아도 된다!
      // return{
      //   ...state,
      //   users : state.users.filter(user=> user.id !== action.id)
      // }
      return produce(state, draft => {
        const index = draft.users.findIndex(user => user.id === action.id);
        draft.users.splice(index, 1);
      })
    default :
      throw new Error('Unhandled action');
  }
}
```

## Immer와 함수형 업데이트

함수형 업데이트를 하는 경우에, `Immer` 를 사용하면 상황에 따라 더 편하게 코드를 작성 할 수 있습니다.

만약에 produce 함수에 두개의 파라미터를 넣게 된다면, 첫번째 파라미터에 넣은 상태를 불변성을 유지하면서 새로운 상태를 만들어주지만,   

만약에 첫번째 파라미터를 생략하고 바로 업데이트 함수를 넣어주게 된다면, 반환 값은 새로운 상태가 아닌 상태를 업데이트 해주는 함수가 됩니다. 설명으로 이해하기가 조금 어려울 수 있는데 코드를 보면 조금 더 이해가 쉬워집니다.

```javascript
const todo = {
  text: 'Hello',
  done: false
};

// state 생략
const updater = produce(draft => {
  draft.done = !draft.done;
});

const nextTodo = updater(todo);

console.log(nextTodo);
// { text: 'Hello', done: true }
```

결국 `produce` 가 반환하는것이 업데이트 함수가 되기 때문에 `useState` 의 업데이트 함수를 사용 할 떄 다음과 같이 구현 할 수 있게 되지요.

```javascript
const [todo, setTodo] = useState({
  text: 'Hello',
  done: false
});

// const onClick = useCallback(() => {
//   setTodo(todo => ({
//     ...todo,
//     done: !todo.done
//   }));
// }, []);

const onClick = useCallback(() => {
  setTodo(
    produce(draft => {
      draft.done = !draft.done;
    })
  );
}, []);
```
---

Immer 은 분명히 정말 편한 라이브러리인것은 사실입니다. 하지만, 확실히 알아둘 점은, <u>성능적으로는 Immer 를 사용하지 않은 코드가 조금 더 빠르다는 점 입니다.</u>

![Immer Performance](https://hackernoon.com/photos/JTw2M3rQabaxNg3EFoNIxjmC1ZB3-t51b330q3)
[출처 : Introducing Immer: Immutability the easy way](https://hackernoon.com/introducing-immer-immutability-the-easy-way-9d73d8f71cb3)

위 성능 분석표는 50,000개의 원소중에서 5,000 개의 원소를 업데이트 하는 코드를 비교 했을때의 결과입니다. 보시면, Immer 의 경우 31ms 걸리는 작업이 (map 을 사용하는) Native Reducer 에서는 6ms 걸린 것을 확인 할 수 있습니다.

그런데, 이렇게 데이터가 많은데도 31ms 가 걸린다는 것은 사실 큰 문제가 아닙니다. 인간이 시각적으로 인지 할 수있는 최소 딜레이는 13ms 라고 합니다 (참고) 그런 것을 생각하면 25ms 의 차이는, 사실 그렇게 큰 차이가 아니기 때문에 걱정할 필요 없습니다. 심지어, <u>데이터가 50,000개 가량 있는게 아니라면 별로 성능 차이가 별로 없을 것이기 때문에 더더욱 걱정하지 않아도 됩니다.</u>

단, Immer 는 JavaScript 엔진의 `Proxy` 라는 기능을 사용하는데, 구형 브라우저 및 react-native 같은 환경에서는 지원되지 않으므로 (Proxy 처럼 작동하지만 Proxy는 아닌) ES5 fallback 을 사용하게 됩니다. ES5 fallback 을 사용하게 되는경우는 191ms 정도로, 꽤나 느려지게 됩니다. 물론, 여전히 데이터가 별로 없다면 크게 걱정 할 필요는 없습니다.

Immer 라이브러리는 확실히 편하기 때문에, 데이터의 구조가 복잡해져서 불변성을 유지하면서 업데이트하려면, 코드가 복잡해지는 상황이 온다면, 이를 사용하는 것을 권장드립니다.

다만, 무조건 사용을 하진 마시고, <mark>가능하면 데이터의 구조가 복잡해지게 되는 것을 방지하세요.</mark> 그리고 어쩔 수 없을 때 Immer 를 사용하는것이 좋습니다. <mark>Immer 를 사용한다고 해도, 필요한곳에만 쓰고, 간단히 처리 될 수 있는 곳에서는 그냥 일반 JavaScript 로 구현하시길 바랍니다.</mark>

