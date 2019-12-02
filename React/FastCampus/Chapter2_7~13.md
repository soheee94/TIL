[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 3. Styled-Components

**CSS in JS** : JS 안에 CSS 를 작성하는 것  
이 기술을 사용하는 라이브러리인 [styled-components](https://www.styled-components.com/) 를 다뤄볼 것입니다.

styled-components 는 현존하는 CSS in JS 관련 리액트 라이브러리 중에서 가장 인기 있는 라이브러리입니다. 이에 대한 대안으로는 [emotion](https://github.com/emotion-js/emotion) 와 [styled-jsx](https://github.com/zeit/styled-jsx)가 있습니다.

## Tagged Template Literal

styled-components 를 사용하기 전에, Tagged Template Literal 이라는 문법에 대하여 짚고 넘어가면, styled-components 가 내부적으로 어떻게 작동하는지 이해 할 수 있습니다. 참고로 이번에 다룰 내용은 조금 이해하기 어려울 수도 있는데요, 완벽히 이해하지 않아도 앞으로 styled-components 를 사용하는데 전혀 지장이 가지 않으니 가볍게 읽고 넘어가 주셔도 됩니다.

아마, Template Literal 에 대해서는 익숙하실 것입니다. 문자열 조합을 더욱 쉽게 할 수 있게 해주는 ES6 문법이죠.

```javascript
const name = "react";
const message = `hello ${name}`;

console.log(message);
// "hello react"
```

우리가 Template Literal 을 사용하면서도, 그 내부에 넣은 자바스크립트 값을 조회하고 싶을 땐 Tagged Template Literal 문법을 사용 할 수 있습니다.

```javascript
const red = "빨간색";
const blue = "파란색";
function favoriteColors(texts, ...values) {
  console.log(texts); //['제가 좋아하는 색은', '과', '입니다.']
  console.log(values); // ['빨간색', '파란색']
}
favoriteColors`제가 좋아하는 색은 ${red}과 ${blue}입니다.`;
```

우리가 앞으로 개발하면서 favoriteColors 같은 함수를 작성할 일은 없으니 이해가기 어려워도 너무 걱정할 필요는 없습니다. 지금은 그냥 저런 문법이 있구나, 정도로 알아두기만헤도 충분합니다.

styled-components 에서는 이런 문법을 사용해서 컴포넌트의 props 를 읽어오기도 하는데요, 지금은 맛보기로만 한번 확인해보세요.

```javascript
const StyledDiv = styled`
  background: ${props => props.color};
`;
```

Tagged Template Literal 을 사용하면 만약 \${} 을 통하여 함수를 넣어줬다면, 해당 함수를 사용해줄 수도 있답니다.

예시

```javascript
function sample(texts, ...fns) {
  const mockProps = {
    title: "안녕하세요",
    body: "내용은 내용내용 입니다."
  };
  return texts.reduce(
    (result, text, i) => `${result}${text}${fns[i] ? fns[i](mockProps) : ""}`,
    ""
  );
}
sample`
  제목: ${props => props.title}
  내용: ${props => props.body}
`;
/*
"
  제목: 안녕하세요
  내용: 내용은 내용내용 입니다.
"
*/
```

## styled-components 사용하기

styled-components 설치

```cmd
$ yarn add styled-components
```

```javascript
// App.js
import styled from "styled-components";

const Circle = styled.div`
  width: 5rem;
  height: 5rem;
  background: black;
  border-radius: 50%;
`;

function App() {
  return <Circle />;
}
```

styled-components 를 사용하면 이렇게 스타일을 입력함과 동시에 해당 스타일을 가진 컴포넌트를 만들 수 있습니다. 만약에 div 를 스타일링 하고 싶으면 styled.div, input 을 스타일링 하고 싶으면 styled.input 이런식으로 사용하면 되는거죠.

Circle 컴포넌트에 color와 huge props 를 넣어줘보겠습니다.

```javascript
// App.js
import React, { useState } from "react";
import styled, { css } from "styled-components";

const Circle = styled.div`
  width: 5rem;
  height: 5rem;
  background: ${props => props.color || "black"};
  border-radius: 50%;
  ${props =>
    props.huge &&
    css`
      width: 10rem;
      height: 10rem;
    `}
`;

function App() {
  return <Circle color="blue" huge />;
}

export default App;
```

이런식으로 <u>여러 줄의 CSS 코드를 조건부로 보여주고 싶다면</u> `css` 를 사용해야합니다. `css` 를 불러와서 사용을 해야 <u>그 스타일 내부에서도 다른 props 를 조회 할 수 있습니다.</u>

## Button 만들기

```javascript
// Button.js
import React from "react";
import styled from "styled-components";

const StyledButton = styled.button`
  /* 공통 스타일 */
  display: inline-flex;
  outline: none;
  border: none;
  border-radius: 4px;
  color: white;
  font-weight: bold;
  cursor: pointer;
  padding-left: 1rem;
  padding-right: 1rem;

  /* 크기 */
  height: 2.25rem;
  font-size: 1rem;

  /* 색상 */
  background: #228be6;
  &:hover {
    background: #339af0;
  }
  &:active {
    background: #1c7ed6;
  }

  /* 기타 */
  & + & {
    margin-left: 1rem;
  }
`;

function Button({ children, ...rest }) {
  return <StyledButton {...rest}>{children}</StyledButton>;
}

export default Button;
```

```javascript
// App.js
const AppBlock = styled.div`
  width: 512px;
  margin: 0 auto;
  margin-top: 4rem;
  border: 1px solid black;
  padding: 1rem;
`;

function App() {
  return (
    <AppBlock>
      <Button>BUTTON</Button>
    </AppBlock>
  );
}
```

## polished의 스타일 관련 유틸 함수 사용하기

Sass 를 사용 할 때에는 `lighten()` 또는 `darken()` 과 같은 유틸 함수를 사용하여 색상에 변화를 줄 수 있었는데요, CSS in JS 에서도 비슷한 유틸 함수를 사용하고 싶다면 [polished](https://polished.js.org/docs/) 라는 라이브러리를 사용하면 됩니다.

```cmd
$ yarn add polished
```

```javascript
// Button.js

import { darken, lighten } from 'polished';

// ...생략

  /* 색상 */
  background: #228be6;
  &:hover {
    background: ${lighten(0.1, '#228be6')};
  }
  &:active {
    background: ${darken(0.1, '#228be6')};
  }
```

색상 코드를 지닌 변수를 Button.js 에서 선언을 하는 대신에 `ThemeProvider` 라는 기능을 사용하여 `styled-components` 로 만드는 모든 컴포넌트에서 조회하여 사용 할 수 있는 전역적인 값을 설정해보겠습니다.

```javascript
// App.js
import styled, { ThemeProvider } from "styled-components";

function App() {
  return (
    <ThemeProvider
      theme={{
        palette: {
          blue: "#228b36",
          gray: "#495057",
          pink: "#f06595"
        }
      }}
    >
      <AppBlock>
        <Button>BUTTON</Button>
      </AppBlock>
    </ThemeProvider>
  );
}
```

이렇게 에서 `theme` 을 설정하면 `ThemeProvider` 내부에 렌더링된 styled-components 로 만든 컴포넌트에서 `palette` 를 조회하여 사용 할 수 있습니다. 한번 `Button` 컴포넌트에서 우리가 방금 선언한 `palette.blue` 값을 조회해봅시다.

```javascript
// Button.js
  /* 색상 */
  ${props => {
    const selected = props.theme.palette.blue;
    return css`
      background: ${selected};
      &:hover {
        background: ${lighten(0.1, selected)};
      }
      &:active {
        background: ${darken(0.1, selected)};
      }
    `;
  }}
```

`ThemeProvider` 로 설정한 값은 `styled-components` 에서 `props.theme` 로 조회 할 수 있습니다. 지금은 `selected` 값을 무조건 `blue` 값을 가르키게 했는데요, 이 부분을 Button 컴포넌트가 `color` props 를 를 통하여 받아오게 될 색상을 사용하도록 수정해보겠습니다.

```javascript
// Button.js
  /* 색상 */
  ${props => {
    const selected = props.theme.palette[props.color];
    return css`
      background: ${selected};
      &:hover {
        background: ${lighten(0.1, selected)};
      }
      &:active {
        background: ${darken(0.1, selected)};
      }
    `;
  }}

// 기본 색상은 blue, > palette 에 있는 값만 가능!
Button.defaultProps = {
  color: 'blue'
};
```

```javascript
// App.js
function App() {
  return (
    <ThemeProvider
      theme={{
        palette: {
          blue: "blue",
          gray: "gray",
          pink: "pink"
        }
      }}
    >
      <AppBlock>
        <Button>BUTTON</Button>
        <Button color="gray">BUTTON</Button>
        <Button color="pink">BUTTON</Button>
      </AppBlock>
    </ThemeProvider>
  );
}
```

리팩토링 1>>

```javascript
// Button.js
 /* 색상 */
  ${({ theme, color }) => {
    const selected = theme.palette[color];
    return css`
      background: ${selected};
      &:hover {
        background: ${lighten(0.1, selected)};
      }
      &:active {
        background: ${darken(0.1, selected)};
      }
    `;
  }}

```

리팩토링 2 >>

```javascript
const colorStyles = css`
  ${({ theme, color }) => {
    const selected = theme.palette[color];
    return css`
      background: ${selected};
      &:hover {
        background: ${lighten(0.1, selected)};
      }
      &:active {
        background: ${darken(0.1, selected)};
      }
    `;
  }}
`;

// ... 생략
    /* 색상 */
  ${colorStyles}
```

`size` props 를 설정하여 버튼의 크기도 다양하게 만들어보겠습니다.

```javascript
// Button.js
const sizes = {
  large: {
    height: "3rem",
    fontSize: "1.25rem"
  },
  medium: {
    height: "2.25rem",
    fontSize: "1rem"
  },
  small: {
    height: "1.75rem",
    fontSize: "0.875rem"
  }
};

const sizeStyles = css`
  /* 크기 */
  ${({ size }) => css`
    height: ${sizes[size].height};
    font-size: ${sizes[size].fontSize};
  `}
`;
```

Button 컴포넌트에 `outline` 이라는 props 를 설정하여 이 값이 true 일 때에는 테두리만 지닌 버튼을 보여주도록 설정해보겠습니다.

```javascript
const colorStyles = css`
  ${({ theme, color }) => {
    const selected = theme.palette[color];
    return css`
      background: ${selected};
      &:hover {
        background: ${lighten(0.1, selected)};
      }
      &:active {
        background: ${darken(0.1, selected)};
      }

      ${props =>
        props.outline &&
        css`
          background: white;
          border: 1px solid ${selected};
          color: ${selected};
          &:hover {
            color: white;
            background: ${selected};
          }
        `}
    `;
  }}
`;
```

`fullWidth` 라는 props 가 주어졌다면 버튼의 크기가 100% 를 차지하도록 만들어보세요.

```javascript
// Button.js
const fullWidthStyles = css`
  ${props =>
    props.fullWidth &&
    css`
      width: 100%;
      justify-content: center;
      & + & {
        margin-top: 1rem;
        margin-left: 0;
      }
    `}
`;
```

## Dialog 만들기

```javascript
// Dialog.js
import React from "react";
import styled from "styled-components";
import Button from "./Button";

const DarkBackground = styled.div`
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.8);
`;

// scss 문법이 적용 되므로 h3과 p를 따로 컴포넌트 만들어주지 않아도 된당
const DialogBlock = styled.div`
  width: 320px;
  padding: 1.5rem;
  background: white;
  border-radius: 2px;

  h3 {
    margin: 0;
    font-size: 1.5rem;
  }

  p {
    font-size: 1.125rem;
  }
`;

const ButtonGroup = styled.div`
  margin-top: 3rem;
  display: flex;
  justify-content: flex-end;
`;

// 기존 Button 컴포넌트 커스터마이징 스타일링
const ShortMarginButton = styled(Button)`
  & + & {
    margin-left: 0.5rem;
  }
`;

function Dialog({
  title,
  children,
  confirmText,
  cancelText,
  visible,
  onConfirm,
  onCancel
}) {
  if (!visible) return null;
  return (
    <DarkBackground>
      <DialogBlock>
        <h3>{title}</h3>
        <p>{children}</p>
        <ButtonGroup>
          <ShortMarginButton color="gray" onClick={onCancel}>
            {cancelText}
          </ShortMarginButton>
          <ShortMarginButton color="pink" onClick={onConfirm}>
            {confirmText}
          </ShortMarginButton>
        </ButtonGroup>
      </DialogBlock>
    </DarkBackground>
  );
}

Dialog.defaultProps = {
  confirmText: "확인",
  cancelText: "취소"
};

export default Dialog;
```

**ShortMarginButton** :  
컴포넌트의 스타일을 커스터마이징 할 때에는 해당 컴포넌트에서 `className` props 를 내부 엘리먼트에게 전달이 되고 있는지 확인해주어야 합니다.

```javascript
const MyComponent = ({ className }) => {
  return <div className={className}></div>;
};

const ExtendedComponent = styled(MyComponent)`
  background: black;
`;
```

참고로 우리가 만든 Button 컴포넌트의 경우에는 `...rest` 를 통하여 전달이 되고 있습니다.

## 트랜지션 구현하기

트랜지션 효과를 적용 할 때에는 CSS Keyframe 을 사용하며, `styled-components` 에서 이를 사용 할 때에는 `keyframes` 라는 유틸을 사용합니다.

```javascript
// Dialog.js
import styled, { keyframes } from "styled-components";
import Button from "./Button";

const fadeIn = keyframes`
    from{
        opacity : 0
    }
    to{
        opacity : 1
    }
`;

const slideUp = keyframes`
    from{
        transform: translateY(200px)
    }
    to{
        transform: translateY(0px)
    }
`;
const DarkBackground = styled.div`
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.8);

  animation-duration: 0.25s;
  animation-timing-function: ease-out;
  animation-name: ${fadeIn};
  animation-fill-mode: forwards;
`;

const DialogBlock = styled.div`
  width: 320px;
  padding: 1.5rem;
  background: white;
  border-radius: 2px;

  h3 {
    margin: 0;
    font-size: 1.5rem;
  }

  p {
    font-size: 1.125rem;
  }

  animation-duration: 0.25s;
  animation-timing-function: ease-out;
  animation-name: ${slideUp};
  animation-fill-mode: forwards;
`;
```

사라지는 효과를 구현하려면 `Dialog` 컴포넌트에서 두개의 로컬 상태를 관리해주어야 합니다. 하나는 현재 트랜지션 효과를 보여주고 있는 중이라는 상태를 의미하는 `animate`, 나머지 하나는 실제로 컴포넌트가 사라지는 시점을 지연시키기 위한 `localVisible` 값입니다.

그리고 `useEffect` 를 하나 작성해주어야 하는데요, `visible` 값이 true 에서 false 로 바뀌는 시점을 감지하여 `animate` 값을 true 로 바꿔주고 setTimeout 함수를 사용하여 250ms 이후 false로 바꾸어 주어야 합니다.

추가적으로, `!visible` 조건에서 `null` 를 반환하는 대신에 `!animate && !localVisible` 조건에서 `null` 을 반환하도록 수정해주어야 합니다.

```javascript
// Dialog.js
// Dialog가 꺼지는 애니메이트가 실행되는지 여부
const [animate, setAnimate] = useState(false);
// 실제로 사라지는 시점 (visible은 꺼지는 버튼 클릭했을 때 시점)
const [localVisible, setLocalVisible] = useState(visible);

useEffect(() => {
  //   Dialog 꺼지라고 요청, 그러나 아직 애니메이트 실행 전
  if (localVisible && !visible) {
    setAnimate(true);
    setTimeout(() => setAnimate(false), 250);
  }
  setLocalVisible(visible);
}, [localVisible, visible]);

if (!animate && !localVisible) return null;
```

```javascript
// Dialog.js
// 생략

const fadeOut = keyframes`
    from{
        opacity : 1
    }
    to{
        opacity : 0
    }
`;

// 생략

const slideDown = keyframes`
    from{
        transform: translateY(0px)
    }
    to{
        transform: translateY(200px)
    }
`;
const DarkBackground = styled.div`
  // 생략

  ${props =>
    props.disapper &&
    css`
      animation-name: ${fadeOut};
    `}
`;

const DialogBlock = styled.div`
  // 생략

  ${props =>
    props.disapper &&
    css`
      animation-name: ${slideDown};
    `}
`;
```
