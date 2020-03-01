# 단위

## px

모니터에 따른 상대적인 값

## %

부모요소에 대하여 상대적인 값

## em

em은 자신의 폰트 사이즈 값에 맞게 영향을 끼친다.

```css
.container {
  font-size: 10px;
  width: 30em; /* 10 * 30 = 300px */
}
```

## rem (root em)

rem은 조상 요소의 폰트사이즈에 영향을 받는다. (html)

```css
html {
  font-size: 15px;
}
.container {
  width: 10rem; /* 10 * 15 = 150px */
}
```

## vw

뷰포트의 너비 값

## vh

뷰포트의 높이 값

## vmin, vmax

뷰포트의 너비와 높이 값에 따른 최대값과 최소값
