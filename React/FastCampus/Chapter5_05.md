[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 4. 리액트 라우터 부가기능

## Switch

Switch 는 여러 Route 들을 감싸서 그 중 규칙이 일치하는 라우트 단 하나만을 렌더링시켜줍니다. Switch 를 사용하면, 아무것도 일치하지 않았을때 보여줄 Not Found 페이지를 구현 할 수도 있습니다.

```javascript
// App.js
<Switch>
  <Route path="/" component={Home} exact />
  <Route path="/about" component={About} />
  <Route path="/profiles" component={Profiles} />
  <Route path="/history" component={HistorySample} />
  <Route
    // path를 따로 정의 하지 않으면 모든 상황에 렌더링 된다!
    render={({ location }) => (
      <div>
        <h2>이 페이지는 존재하지 않아욧!</h2>
        <p>{location.pathname}</p>
      </div>
    )}
  />
</Switch>
```

## NavLink

NavLink 는 Link 랑 비슷한데, 만약 현재 경로와 Link 에서 사용하는 경로가 일치하는 경우 <u>특정 스타일 혹은 클래스를 적용 할 수 있는 컴포넌트입니다.</u>

```javascript
// Profiles.js
<li>
  <NavLink
    to="/profiles/velopert"
    activeStyle={{ background: "black", color: "white" }}
  >
    velopert
  </NavLink>
</li>
```

만약에 스타일이 아니라 CSS 클래스를 적용하시고 싶으면 `activeStyle` 대신 `activeClassName` 을 사용하시면 됩니다.

## 기타

- [Redirect](https://reacttraining.com/react-router/web/example/auth-workflow) : 페이지 리디렉트 하는 컴포넌트
- [Prompt](https://reacttraining.com/react-router/web/example/preventing-transitions) : 이전에 사용했던 history.block 의 컴포넌트 버전
- [Route Config](https://reacttraining.com/react-router/web/example/route-config): JSX 형태로 라우트를 선언하는 것이 아닌 Angular 나 Vue 처럼 배열/객체를 사용하여 라우트 정의하기
- [Memory Router](https://reacttraining.com/react-router/web/api/MemoryRouter) : 실제로 주소는 존재하지는 않는 라우터. 리액트 네이티브나, 임베디드 웹앱에서 사용하면 유용하다.

[공식매뉴얼](https://reacttraining.com/react-router/web/guides/philosophy)
