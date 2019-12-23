[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 11. connect 함수

## 소개

`connect` 함수는 컨테이너 컴포넌트를 만드는 또 다른 방법입니다. 클래스형 컴포넌트로 작성하는 경우에 `connect` 함수를 사용해야 된다. 그래서 앞으로 새로운 컨테이너 컴포넌트를 만들 때에는 `connect` 를 사용하는 일이 별로 없긴 하겠지만, 이 함수가 어떻게 작동하는지 이해는 해야하기 때문에, 다뤄보도록 한다

## HOC란?

`connect`는 HOC입니다. HOC란, Higher-Order Component 를 의미하는데요, 이는 리액트 컴포넌트를 개발하는 하나의 패턴으로써, 컴포넌트의 로직을 재활용 할 때 유용한 패턴입니다. 예를 들어서, 특정 함수 또는 값을 props 로 받아와서 사용하고 싶은 경우에 이러한 패턴을 사용합니다. 리액트에 Hook이 도입되기 전에는 HOC 패턴이 자주 사용되어왔으나, 리액트에 Hook 이 도입된 이후에는 HOC를 만들 이유가 없어졌습니다. 대부분의 경우 Hook으로 대체 할 수 있기 때문이지요. 심지어, 커스텀 Hook을 만드는건 굉장히 쉽기도 합니다.

HOC를 직접 구현하게 되는 일은 거의 없기 때문에 지금 시점에 와서 HOC를 직접 작성하는 방법을 배워보거나, 이해하기 위해 시간을 쏟을 필요는 없습니다.

HOC의 용도는 <u>"컴포넌트를 특정 함수로 감싸서 특정 값 또는 함수를 props로 받아와서 사용 할 수 있게 해주는 패턴"</u>이라는 것 정도만 알아두시면 됩니다.

HOC 컬렉션 라이브러리인 recompose라는 라이브러리를 보시면 HOC를 통해서 어떤 것을 하는지 갈피를 잡을 수 있습니다.

## connect 사용

```javascript
// containers/CounterContainer.js
import React from "react";
import { connect } from "react-redux";
import { increase, decrease, setDiff } from "../modules/counter";
import Counter from "../components/Counter";

function CounterContainer({ number, diff, onIncrease, onDecrease, onSetDiff }) {
  // 상태와 액션을 디스패치 하는 함수들을 props 로 넣어준다.
  return (
    <Counter
      number={number}
      diff={diff}
      onIncrease={onIncrease}
      onDecrease={onDecrease}
      onSetDiff={onSetDiff}
    />
  );
}

// mapStateToProps 는 리덕스 스토어의 상태를 조회해서 어떤 것들을 props 로 넣어줄지 정의합니다.
// 현재 리덕스 상태를 파라미터로 받아옵니다.
const mapStateToProps = state => ({
  number: state.counter.number,
  diff: state.counter.diff
});

// mapDispatchToProps 는 액션을 디스패치하는 함수를 만들어서 props로 넣어줍니다.
// dispatch 를 파라미터로 받아옵니다.
const mapDispatchToProps = dispatch => ({
  onIncrease: () => dispatch(increase()),
  onDecrease: () => dispatch(decrease()),
  onSetDiff: diff => dispatch(setDiff(diff))
});

// connect 함수에는 mapStateToProps, mapDispatchToProps 를 인자로 넣어주세요.
export default connect(mapStateToProps, mapDispatchToProps)(CounterContainer);

/* 위 코드는 다음과 동일합니다.
  const enhance = connect(mapStateToProps, mapDispatchToProps);
  export defualt enhance(CounterContainer);
*/
```

여기서 `mapDispatchToProps` 는 `redux` 라이브러리에 내장된 `bindActionCreators` 라는 함수를 사용하면 다음과 같이 리팩토링 할 수 있습니다.

```javascript
// bindActionCreators 를 사용하면, 자동으로 액션 생성 함수에 dispatch 가 감싸진 상태로 호출 할 수 있습니다.
const mapDispatchToProps = dispatch =>
  bindActionCreators(
    {
      increase,
      decrease,
      setDiff
    },
    dispatch
  );
```

`connect` 함수에서는 `mapDispatchToProps가` 함수가 아니라 아예 객체형태일때에는 `bindActionCreators를` 대신 호출해줍니다.

```javascript
// 함수가 아닌 객체 형태일 때는 bindActionCreator를 connect에서 대신 해준다.
const mapDispatchToProps = {
  increase,
  decrease,
  setDiff
};
```

## connect, 알아둬야 하는 것들

### 1. mapStateToProps 의 두번째 파라미터 ownProps

`mapStateToProps`에서는 두번째 파라미터 `ownProps`를 받아올 수 있는데요 이 파라미터는 생략해도 되는 파라미터입니다. 이 값은 우리가 컨테이너 컴포넌트를 렌더링 할때 직접 넣어주는 `props` 를 가르킵니다. 예를 들어서

`<CounterContainer myValue={1} />` 이라고 하면 `{ myValue: 1 }` 값이 `ownProps`가 되죠.

이 두번째 파라미터는 다음과 같은 용도로 활용 할 수 있습니다.

```javascript
const mapStateToProps = (state, ownProps) => ({
  todo: state.todos[ownProps.id]
});
```

리덕스에서 어떤 상태를 조회 할 지 설정하는 과정에서 현재 받아온 `props`에 따라 다른 상태를 조회 할 수 있죠.

### 2. connect 의 3번째 파라미터 mergeProps

`mergeProps`는 `connect` 함수의 세번째 파라미터이며, 생략해도 되는 파라미터입니다. 이 파라미터는 컴포넌트가 실제로 전달받게 될 `props` 를 정의합니다.

```javascript
(stateProps, dispatchProps, ownProps) => Object
이 함수를 따로 지정하지 않으면 결과는 { ...ownProps, ...stateProps, ...dispatchProps } 입니다.
```

(사실상 사용하게 될 일이 없습니다.)

### 3. connect의 4번째 파라미터 options

`connect` 함수를 사용 할 때 이 컨테이너 컴포넌트가 어떻게 동작할지에 대한 옵션을 4번째 파라미터를 통해 설정 할 수 있습니다. 이는 생략해도 되는 파라미터입니다. 이 옵션들은 따로 커스터마이징하게 되는일이 별로 없습니다. 자세한 내용은 링크를 참조하세요. 이 옵션을 통하여 Context 커스터마이징, 최적화를 위한 비교 작업 커스터마이징, 및 ref 관련 작업을 할 수 있습니다.
