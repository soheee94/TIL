# Jest, Enzyme을 통한 리액트 컴포넌트 유닛 테스
https://velopert.com/3587

## 유닛테스팅이란

소프트웨어를 기능별로 쪼개고, 그리고 그 기능 내부에서 사용되는 함수들도, 쪼개고 쪼개서 아주 작은 단위로 테스팅을 하는 것을 의미
(작업을 하나 하나 나눠서, 각 작업이 잘 이뤄지는지를 확인한다.)

### 어떠한 경우에 유용할까?
예를들어 여러분이 코드 A, B 를 작성했고, 팀원이 코드 C, D 를 구현했다고 가정해봅시다.

그리고, G 라는 기능을 구현하기 위하여, 코드 A 와 C 가 사용되었다고 가정을 해봅시다. G 기능을 구현하면서, 여러분이 여러분의 팀원이 작성한 코드 C 를 아주 조금 수정했습니다. 코드 C 가 잘 작동하는것을 확인했고, G도 잘 작동하는것을 확인 했는데요, 갑자기 의도치 않게 C 기능이 고장나버렸습니다.

만약에, 유닛 테스팅을 했더라면, C 기능이 고장나버린것을 코드를 작성하고 바로 발견 할 수 있지만, 유닛 테스팅을 하지 않을 경우에는, 어쩌다가 해당 버그를 발견하지 못 할 가능성도 있습니다.

간단한 이야기를 길게 설명했는데, 짧게 정리하자면 다음과 같습니다:

유닛 테스팅은, 내가 작성한 코드가 다른 코드들을 망가뜨리지 않도록, 적어도 우리가 사전에 정의한 상황속에서 보장해줍니다.

## 리액트 컴포넌트 테스팅

리액트 프로젝트 또한, 컴포넌트 단위로 하나하나 테스트 로직을 정해줄 수 있다. 리액트 컴포넌트를 테스팅할 때는, 주로 다음과 같은 형식으로 하게된다.
1. 특정 props에 따라 컴포넌트가 크래쉬 없이 잘 렌더링이 되는지 확인
2. 이전에 렌더링 했던 결과와, 지금 렌더링한 결과가 일치하는지 확인
3. 특정 DOM 이벤트를 시뮬레이트 하여, 원하는 변화가 제대로 발생하는지 확인
4. 렌더링 된 결과물을 이미지로 저장을 하여 픽셀을 하나하나 확인해서 모두 일치하는지 확인 (스토리북을 통해서 하는것이 효율적이고 편함)

## 프로젝트 생성, 코드 준비

### Counter.js
```jsx
import React, { useState } from "react";

function Counter() {
  const [value, setValue] = useState(1);
  const onIncrease = () => setValue(value + 1);
  const onDecrease = () => setValue(value - 1);

  return (
    <div>
      <h1>Counter</h1>
      <h2>{value}</h2>
      <button onClick={onIncrease}>+</button>
      <button onClick={onDecrease}>-</button>
    </div>
  );
}

export default Counter;
```


### NameForm.js
```jsx
import React, { useState } from "react";

function NameForm({ onInsert }) {
  const [name, setName] = useState("");
  const onChange = (e) => setName(e.target.value);
  const onSubmit = (e) => {
    onInsert(name);
    setName("");
    e.preventDefault();
  };

  return (
    <form onSubmit={onSubmit}>
      <label>name</label>
      <input type="text" value={name} onChange={onChange} />
      <button type={"submit"}>submit</button>
    </form>
  );
}

NameForm.defaultProps = {
  onSubmit: () => console.warn("onSubmit not defined"),
};

export default NameForm;
```

### NameList.js
```jsx
import React from "react";

function NameList({ names }) {
  return (
    <>
      {names.map((name, i) => (
        <li key={i}>{name}</li>
      ))}
    </>
  );
}

NameList.defaultProps = {
  names: [],
};

export default NameList;
```

### App.js
```jsx
import React, { useState } from "react";
import "./App.css";
import Counter from "./components/Counter";
import NameForm from "./components/NameForm";
import NameList from "./components/NameList";

function App() {
  const [names, setNames] = useState(["hazle", "sohee"]);
  const onInsert = (name) => setNames(names.concat(name));

  return (
    <div>
      <Counter />
      <hr />
      <h1>이름 목록</h1>
      <NameForm onInsert={onInsert} />
      <NameList names={names} />
    </div>
  );
}

export default App;
```

## 스냅샷 테스팅

스내샵 테스팅은, 컴포넌트를 주어진 설정으로 렌더링하고, 그 결과물을 파일로 저장한다. 그리고 다음번에 테스팅을 진행하게 되었을 때, 이전의 결과물과 일치하는지 확인한다.

초기 렌더링 결과도 비교할 수 있지만, 컴포넌트의 내부 메소드를 호출시키고 다시 렌더링 시켜서 그 결과물도 스냅샷을 저장시켜서 각 상황에 모두 이전에 렌더링 했던 결과와 일치하는지 비교를 할 수 있다.

스냅샷 테스팅을 하기 위하여, 우선 `react-test-renderer`를 설치
```cmd
yarn add --dev react-test-renderer
```


### Counter.test.js
```jsx
import React from "react";
import renderer from "react-test-renderer";
import Counter from "./Counter";

describe("Counter", () => {
  let component = null;

  it("renders correctly", () => {
    component = renderer.create(<Counter />);
  });

  it("matches snapshot", () => {
    const tree = component.toJSON();
    expect(tree).toMatchSnapshot();
  });
});
```

테스트를 하게 될 때 주요 키워드는, 다음과 같습니다:

- describe
- it
- expect

우리가 코드 테스팅 로직을 쪼개고 쪼갤 대, 일단 가장 작은 단위는 it입니다. 예를 들면
```javascript
it('is working!', () => {
  expect(something).toBeTruthy();
})
```

it 내부에서는 expect를 통하여 특정 값이 우리가 예상한 값이 나왔는지 확인을 할 수 있습니다. 그리고 여러개의 it을 describe안에 넣을 수 있게 되며, describe안에는 또 여러개의 describe를 넣을 수 있습니다.


```javascript
describe('...', () => {
  describe('...', () => {
    it('...', () => { });
    it('...', () => { });
  });
  describe('...', () => {
    it('...', () => { });
    it('...', () => { });
  });
});
```
describe 와 it 에서 첫번째 파라미터는 작업의 설명을 넣어주게 되는데, describe 에서는 어떤 기능을 확인하는지, 그리고 it 부분에선 무엇을 검사해야 되는지에 대한 설명을 넣으시면 됩니다.

설명을 넣을때는, 주로 영어로 작성합니다. 하지만, 영어로 작성하는 것이 익숙하지 않다면, 다음과 같이 한글로 작성해도 무방합니다:

## 내부 메소드 호출 및 state 조회

react-test-render를 하면 실제로 컴포넌트가 렌더링 되기 때문에, 컴포넌트의 state와 메소드에도 접근할 수 있습니다.

메소드를 실행시켜서 state를 업데이트 시키고, 리렌더링을 하여 변화에 따라 우리가 의도한대로 렌더링이 되는지, 스냅샷을 통하여 비교하겠습니다!

### Counter.test.js

```jsx
import React from 'react';
import renderer from 'react-test-renderer';
import Counter from './Counter';

describe('Counter', () => {
  let component = null;

  it('renders correctly', () => {
    component = renderer.create(<Counter />);
  });

  it('matches snapshot', () => {
    const tree = component.toJSON();
    expect(tree).toMatchSnapshot();
  })

  // increase 가 잘 되는지 확인
  it('increases correctly', () => {
    component.getInstance().onIncrease();
    expect(component.getInstance().state.value).toBe(2); // value 값이 2인지 확인
    const tree = component.toJSON(); // re-render
    expect(tree).toMatchSnapshot(); // 스냅샷 비교
  });

  // decrease 가 잘 되는지 확인
  it('decreases correctly', () => {
    component.getInstance().onDecrease();
    expect(component.getInstance().state.value).toBe(1); // value 값이 1인지 확인
    const tree = component.toJSON(); // re-render
    expect(tree).toMatchSnapshot(); // 스냅샷 비교
  });
});
```

`testrenderer.getInstance()`는 최상위 엘리먼트에 대응하는 인스턴스가 존재하면 값을 반환한다. 최상위 엘리먼트가 함수 컴포넌트일 경우, 함수 컴포넌트에는 인스턴스가 없기 때문에 작동하지 않는다!!!
-> 위의 경우 함수형 컴포넌트로 바꿔 작성해서 `TypeError: Cannot read property 'onIncrease' of null` 가 발생했다.

## Enzyme을 통한 DOM 시뮬레이션

Enzyme은 airbnb에서 만든 리액트 컴포넌트 테스팅 도구입니다. 이 도구를 사용하면 더욱 세밀한 리액트 컴포넌트 테스팅을 할 수 있다.

Enzyme을 통해서 DOM 이벤트를 시뮬레이트 할 수도 있고(버튼 클릭, 인풋 수정, 폼 등록 등), 모든 라이프사이클이 문제없이 돌아가는지도 확인할 수 있다.

### 설치 및 적용
```cmd
yarn add enzyme enzyme-adapter-react-16
```

테스트 설정 파일 수정 (src/setupTests.js)

```javascript
import { configure } from "enzyme";
import Adapter from "enzyme-adapter-react-16";

configure({
  adapter: new Adapter(),
});

```


기존의 NameForm 테스트 코드에서 작성하던 react-test-renderer 대신에, Enzyme을 사용해보겠습니다

```jsx
import React from "react";
// import renderer from "react-test-renderer";
import NameForm from "./NameForm";
import { shallow } from "enzyme";

describe("NameForm", () => {
  let component = null;

  it("renders correctly", () => {
    // component = renderer.create(<NameForm />);
    component = shallow(<NameForm />);
  });

  it("matches snapshot", () => {
    const tree = component.toJSON();
    expect(tree).toMatchSnapshot();
  });
});

```

## DOM 시뮬레이션 해보기

```javascript
  describe("insert new text", () => {
    it("has a form", () => {
      expect(component.find("form").exists()).toBe(true);
    });
    it("has an input", () => {
      expect(component.find("input").exists()).toBe(true);
    });
    it("simulates input change", () => {
      const mockedEvent = {
        target: {
          value: "hello",
        },
      };

      component.find("input").simulate("change", mockedEvent);
      expect(component.state().name).toBe("hello");
    });
  });
```