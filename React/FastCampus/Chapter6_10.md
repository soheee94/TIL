[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 10. useSelector 최적화

프레젠테이셔널 컴포넌트에서는 `React.memo`를 사용하여 리렌더링 최적화
컨테이너 컴포넌트에서는 ?

기본적으로, `useSelector`를 사용해서 리덕스 스토어의 상태를 조회 할 땐 만약 상태가 바뀌지 않았으면 리렌더링하지 않습니다.

TodosContainer 의 경우 카운터 값이 바뀔 때 todos 값엔 변화가 없으니까, 리렌더링되지 않는것이죠.

```javascript
const todos = useSelector(state => state.todos);
```

반면 CounterContainer 를 확인해볼까요?

```javascript
// 새 객체 생성중
const { number, diff } = useSelector(state => ({
  number: state.counter.number,
  diff: state.counter.diff
}));
```

CounterContainer에서는 사실상 `useSelector` Hook 을 통해 매번 렌더링 될 때마다 새로운 객체 `{ number, diff }`를 만드는 것이기 때문에 상태가 바뀌었는지 바뀌지 않았는지 확인을 할 수 없어서 낭비 렌더링이 이루어지고 있는 것 입니다.

최적화 하기 위한 두가지 방법

1. `useSelector` 여러번 사용하기

```javascript
// CounterContainer.js
const number = useSelector(state => state.counter.number);
const diff = useSelector(state => state.counter.diff);
```

2. react-redux의 `shallowEqual` 함수를 `useSelector` 의 두번째 인자로 전달

```javascript
const { number, diff } = useSelector(
  state => ({
    number: state.counter.number,
    diff: state.counter.diff
  }),
  shallowEqual
);
```

`useSelector` 의 두번째 파라미터는 `equalityFn` 인데요,

```javascript
equalityFn?: (left: any, right: any) => boolean
```

이전 값과 다음 값을 비교하여 `true`가 나오면 리렌더링을 하지 않고 `false`가 나오면 리렌더링을 합니다.

`shallowEqual은` react-redux에 내장되어있는 함수로서, 객체 안의 **가장 겉에 있는 값**들을 모두 비교해줍니다.

```javascript
const object = {
  a: {
    x: 3,
    y: 2,
    z: 1
  },
  b: 1,
  c: [{ id: 1 }]
};
```

가장 겉에 있는 값은 object.a, object.b, object.c 입니다. `shallowEqual` 에서는 해당 값들만 비교하고 `object.a.x` 또는 `object.c[0]` 값은 비교하지 않습니다.

-> 얕게 비교! 정확한 변경내역은 알 수 없다.
