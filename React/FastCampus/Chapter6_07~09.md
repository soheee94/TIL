[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 7~9. 할 일 목록 구현하기

## 프레젠테이셔널 컴포넌트 구현하기

```javascript
// components/Todo.js
// TodoItem, TodoList, Todos
// ★컴포넌트를 여러개 만드는 이유는 컴포넌트의 리렌더링 성능을 최적화하기 위해서!
// 편의상 한 파일에 모두 작성, 취향에 따라 각각 다른 파일에 분리해도 무관

import React, { useState } from "react";

// 컴포넌트 최적화를 위해 React.memo를 사용
const TodoItem = React.memo(function TodoItem({ todo, onToggle }) {
  return (
    <li
      style={{ textDecoration: todo.done ? "line-through" : "none" }}
      onClick={() => onToggle(todo.id)}
    >
      {todo.text}
    </li>
  );
});

const TodoList = React.memo(function TodoList({ todos, onToggle }) {
  return (
    <ul>
      {todos.map(todo => (
        <TodoItem key={todo.id} todo={todo} onToggle={onToggle} />
      ))}
    </ul>
  );
});

function Todos({ todos, onToggle, onCreate }) {
  // 리덕스를 사용한다고 해서 모든 상태를 리덕스에서 관리해야하는 것은 아닙니다.
  const [text, setText] = useState("");
  const onChange = e => setText(e.target.value);
  const onSubmit = e => {
    e.preventDefault();
    onCreate(text);
    setText("");
  };
  return (
    <div>
      <form onSubmit={onSubmit}>
        <input
          value={text}
          onChange={onChange}
          placeholder="할 일 입력 하세요!"
        />
        <buttom type="submit">등록</buttom>
      </form>
      <TodoList todos={todos} onToggle={onToggle} />
    </div>
  );
}

export default React.memo(Todos);
```

## 컨테이너 컴포넌트 만들기

```javascript
// containers/TodoContainer.js
import React, { useCallback } from "react";
import Todos from "../components/Todos";
import { useSelector, useDispatch } from "react-redux";
import { addTodo, toggleTodo } from "../modules/todos";

function TodoContainer() {
  const todos = useSelector(state => state.todos);
  const dispatch = useDispatch();

  const onCreate = text => dispatch(addTodo(text));
  // useSelector 에서 꼭 객체를 반환 할 필요는 없습니다.
  // 한 종류의 값만 조회하고 싶으면 그냥 원하는 값만 바로 반환하면 됩니다.
  const onToggle = useCallback(id => dispatch(toggleTodo(id)), [dispatch]);
  // 최적화를 위해 useCallback 사용
  return <Todos todos={todos} onCreate={onCreate} onToggle={onToggle} />;
}

export default TodoContainer;
```
