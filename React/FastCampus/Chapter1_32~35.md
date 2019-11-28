[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 32~35. 리액트 개발 할 때 사용하면 편리한 도구들 - Prettier, ESLint, Snippet

## 1. Prettier

[Prettier](https://prettier.io/) 는 자동으로 코드의 스타일을 관리해주는 도구

[Prettier 기본 설정](https://prettier.io/docs/en/configuration.html)


```json
//.prettierrc
{
  "trailingComma": "es5",
  "tabWidth": 4,
  "semi": false,
  "singleQuote": true
}
```

### trailingComma
객체 또는 배열이 여러줄로 구성되어 있을 때 쉼표 설정

### tabWidth
들여쓰기 크기

### semi
세미콜론 사용 여부

### singleQuote
문자열 쓸 때 ' 를 사용할지 "를 사용할지 여부

## 2. ESLint
ESLint 는 자바스크립트의 문법을 확인해주는 도구입니다.

ESLint 에는 정말 다양한 규칙들이 있습니다. 지금은 기본적인 규칙들만 적용된 상태인데요, 다양한 ESLint 설정이 되어있는 묶어서 라이브러리로 제공이 되기도 합니다.
- eslint-config-airbnb
- eslint-config-google
- eslint-config-standard

## 3. Snippet

Snippet 은 도구라기보단, 에디터마다 내장되어있는 기능입니다. 한국어로는 "코드 조각" 이라고도 부르는데요, Snippet 의 용도는 자주 사용되는 코드에 대하여 단축어를 만들어서 코드를 빠르게 생성해내는 것 입니다.

[스니펫 사용 변수 참고](https://code.visualstudio.com/docs/editor/userdefinedsnippets)