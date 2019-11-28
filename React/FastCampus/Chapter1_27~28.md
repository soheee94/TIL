[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 27~28. 클래스형 컴포넌트

클래스형 컴포넌트를 사용하는 일은 거의 없겠지만 그래도 알아둘 필요는 있습니다. 나중에 클래스형 컴포넌트를 사용하는 프로젝트를 유지보수하게 되는 일이 있을 수도 있고, 함수형 컴포넌트 + Hooks 로 못하는 작업이 2개정도 있긴 합니다. 추가적으로, 옛날에 만들어진 리액트 관련 라이브러리의 경우에는 Hooks 지원이 아직 안되는 경우도 있고, react-native 관련 라우터 라이브러리인 react-native-navigation 의 경우에도 클래스형 컴포넌트를 어쩔 수 없이 써야 하는 일이 종종 있습니다.

## 클래스형 컴포넌트를 만드는 방법

```javascript
// Hello.js
import React, { Component } from 'react';

// 클래스형 컴포넌트
class Hello extends Component {
    static defaultProps = {
        name:'이름없음'
    }
    render(){
        const { color, name, isSpecail } = this.props;
        return(
            <div style={{ color }}>
                {isSpecial && <b>*</b>}
                안녕하세요 {name}
            </div>
        )
    }
}

// 함수형 컴포넌트
// function Hello({ color, name, isSpecial }) {
//   return (
//     <div style={{ color }}>
//       {isSpecial && <b>*</b>}
//       안녕하세요 {name}
//     </div>
//   );
// }

// 이 방법도 가능
// Hello.defaultProps = {
//   name: '이름없음'
// };

export default Hello;
```

클래스형 컴포넌트에서는 `render()` 메서드가 꼭 있어야 합니다. 이 메서드에서 렌더링하고 싶은 `JSX` 를 반환하시면 됩니다. 그리고, `props` 를 조회 해야 할 때에는 `this.props` 를 조회하시면 됩니다.

`defaultProps` 를 설정하는 것은 이전 함수형 컴포넌트에서 했을 때와 똑같이 해주셔도 되고, 다음과 같이 클래스 내부에 `static` 키워드와 함께 선언 할 수도 있습니다.

## 커스텀 메서드 만들기

```javascript
// 기존 함수형 컴포넌트 메서드
const onIncrease = () => {
  dispatch({ type: 'INCREMENT' });
};
```

클래스형 컴포넌트에서는 render 함수 내부에서 선언은 할 수 있기는 있지만, 일반적으로 그렇게 하지는 않고 클래스 안에 커스텀 메서드를 선언합니다.

```javascript
// Counter.js
import React, { Component } from 'react';

class Counter extends Component {
  handleIncrease() {
    console.log('increase');
  }

  handleDecrease() {
    console.log('decrease');
  }

  render() {
    return (
      <div>
        <h1>0</h1>
        <button onClick={this.handleIncrease}>+1</button>
        <button onClick={this.handleDecrease}>-1</button>
      </div>
    );
  }
}

export default Counter;
```
클래스 내부에 종속된 함수를 **"메서드"** 라고 부릅니다. 클래스에서 커스텀 메서드를 만들게 될 때에는 보통 이름을 `handle...` 이라고 이름을 짓습니다. 단, 정해진 규칙은 아니므로 꼭 지키실 필요는 없습니다.

이제 버튼을 누르면 increase 또는 decrease 라는 문구가 나타날 것입니다.

우리가 추후 상태를 업데이트 할 때에는 이 메서드에서 `this.setState` 라는 함수를 사용해야 하는데요, 여기서 this 는 컴포넌트 인스턴스를 가르켜야 하는데, 현재 구현한 handleIncrease 와 handleDecrease에서는 this 를 조회하려고 하면 컴포넌트 인스턴스를 가르키지 않게 됩니다.

handleIncrease 에서 `this` 를 콘솔에 출력하면 `undefined` 가 나타나게 됩니다.

이렇게 되는 이유는, 우리가 만든 메서드들을 각 button 들의 이벤트로 등록하게 되는 과정에서 <u>각 메서드와 컴포넌트 인스턴스의 관계가 끊겨버리기 때문입니다.</u>

이를 해결하기 위해서 할 수 있는 방법은 총 3가지 방법이 있습니다.

1. 클래스의 생성자 메서드 `constructor` 에서 `bind` 작업

```javascript
constructor(props) {
  super(props);
  this.handleIncrease = this.handleIncrease.bind(this);
  this.handleDecrease = this.handleDecrease.bind(this);
}
```

함수의 `bind` 를 사용하면, <u>해당 함수에서 가르킬 `this` 를 직접 설정</u>해줄 수 있습니다. `constructor` 에서는 `props` 파라미터로 받아오고 `super(props)` 를 호출해주어야 하는데, 이는 이 클래스가 컴포넌트로서 작동 할 수 있도록 해주는 Component 쪽에 구현되어있는 생성자 함수를 먼저 실행해주고, 우리가 할 작업을 하겠다 라는 것을 의미합니다.

2. 커스텀 메서드를 선언 할 때 화살표 함수 문법을 사용

```javascript
handleIncrease = () => {
  console.log('increase');
  console.log(this);
};

handleDecrease = () => {
  console.log('decrease');
};
```

클래스형 컴포넌트에서 화살표 함수를 사용해서 메서드를 구현 하는 것은 클래스에 특정 속성을 선언 할 수 있게 해주는 [class-properties][https://babeljs.io/docs/en/babel-plugin-proposal-class-properties] 라는 문법을 사용하는데 이 문법은 아직 정식 자바스크립트 문법이 아닙니다. 단, CRA 로 만든 프로젝트에는 적용이 되어있는 문법이기 때문에 바로 사용 할 수 있습니다.

보통 CRA 로 만든 프로젝트에서는 커스텀 메서드를 만들 때 이 방법을 많이 사용합니다. 그리고, 가장 편하기도 합니다.

3. onClick 에서 새로운 함수를 만들어서 전달을 하는 것인데 이렇게 사용하는 것을 않습니다. 렌더링 할 때마다 함수가 새로 만들어지기 때문에 나중에 컴포넌트 최적화 할 때 까다롭습니다.

```javascript
<button onClick={() => this.handleIncrease()}>+1</button>
<button onClick={() => this.handleDecrease()}>-1</button>
```

## 상태 선언하기

클래스형 컴포넌트에서 상태를 관리 할 때에는 `state` 라는 것을 사용합니다. `state` 를 선언 할 때에는 `constructor` 내부에서 `this.state` 를 설정해주시면 됩니다.

```javascript
// Counter.js
constructor(props) {
  super(props);
  this.state = {
    counter: 0
  };
}

// 생략
render() {
  return (
    <div>
      <h1>{this.state.counter}</h1>
      <button onClick={this.handleIncrease}>+1</button>
      <button onClick={this.handleDecrease}>-1</button>
    </div>
  );
}
```

클래스형 컴포넌트의 `state` 는 무조건 객체형태여야 합니다.

`render` 메서드에서 `state` 를 조회하려면 `this.state` 를 조회하시면 됩니다.

우리가 화살표 함수 문법을 사용하여 메서드를 작성 할 수 있게 해줬던 class-properties 문법이 적용되어 있다면 굳이 `constructor` 를 작성하지 않고도 다음과 같이 `state` 를 설정해줄 수 있습니다.

```javascript
class Counter extends Component {
  state = {
    counter: 0
  };
  handleIncrease = () => {
    console.log('increase');
    console.log(this);
  };
  //생략
}
```
CRA 로 만든 프로젝트에서는 보통 이렇게 많이 작성합니다.

## 상태 업데이트 하기

상태를 업데이트해야 할 때에는 `this.setState` 함수를 사용하면 됩니다.

```javascript
handleIncrease = () => {
  this.setState({
    counter: this.state.counter + 1
  });
};

handleDecrease = () => {
  this.setState({
    counter: this.state.counter - 1
  });
};
```
`this.setState` 를 사용 할 떄는 위 코드 처럼 객체 안에 업데이트 하고 싶은 값을 넣어서 호출해주면 되는데요, 만약에 다음과 같이 `state` 에 다른 값이 들어있다면

```javascript
// state 내 객체 형태가 아닐 때, 불변성 유지 x
state = {
  counter: 0,
  fixed: 1
};
handleIncrease = () => {
  this.setState({
    counter: this.state.counter + 1
  });
};
```
`this.setState` 를 할 때 파라미터에 넣는 객체에 `fixed` 값을 넣어주지 않아도 값이 유지됩니다.  // 불변성 유지 X  
하지만, 클래스형 컴포넌트의 `state` 에서 객체 형태의 상태를 관리해야 한다면, 불변성을 관리해줘가면서 업데이트를 해야 합니다.

## setState 의 함수형 업데이트

함수형 업데이트는 <u>보통 한 함수에서 `setState` 를 여러번에 걸쳐서 해야 되는 경우에 사용하면 유용합니다</u>. 예를 들어서 다음과 같은 코드는 `setState` 를 두번 사용하면서 `state.counter` 값에 1을 더해주는 작업을 두번주지만, 실제로 2가 더해지지지는 않습니다.

```javascript
handleIncrease = () => {
  this.setState(state => ({
    counter: state.counter + 1
  }));
};

handleDecrease = () => {
  this.setState(state => ({
    counter: state.counter - 1
  }));
};
```

하지만, 다음과 같이 함수형 업데이트로 처리해주면 값이 2씩 더해집니다.

```javascript
handleIncrease = () => {
  this.setState(state => ({
    counter: state.counter + 1
  }));
  this.setState(state => ({
    counter: state.counter + 1
  }));
};
```
업데이트 할 객체를 넣어주는 `setState` 에서 2씩 더해지지 않는 이유는 `setState` 를 한다고 해서 상태가 바로 바뀌는게 아니기 때문입니다. `setState` 는 <u>단순히 상태를 바꾸는 함수가 아니라 **상태를 바꿔달라고 요청해주는 함수로 이해**를 해야합니다</u> (참고). 성능적인 이유 때문에 리액트에서는 상태가 바로 업데이트 되지 않고 비동기적으로 업데이트가 됩니다.

만약에, 상태가 업데이트 되고 나서 어떤 작업을 하고 싶다면 다음과 같이 setState 의 두번째 파라미터에 콜백함수를 넣어줄 수도 있습니다.

```javascript
handleIncrease = () => {
  this.setState(
    {
      counter: this.state.counter + 1
    },
    () => {
      console.log(this.state.counter);
    }
  );
};
```