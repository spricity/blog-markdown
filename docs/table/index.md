## 简介

对于一个网站或者一个应用其网页的呈现形式需要通过绘制(paint)来呈现，绘制页面元素其花费的性能代价是非常昂贵的。如果这种绘制过程如果一直在运行着，从没有停止，那么将会严重影响页面性能，并且这种影响是连锁的，会使页面变得越来越慢，特别是对现有的手持设备（如ipad、iphone等）。本文将指出有哪些什么因素导致浏览器在不停的绘制页面元素，并阐述我们应该怎样做，能够有效的避免页面产生不必要的重绘。

## Painting: A super-quick tour

浏览器最主要的任务之一是根据页面的DOM结构和CSS样式转换成点显示在屏幕上，这是一个相当复杂的过程。浏览器一开始先读取文本标记，然后根据这些标记创建DOM树，对CSS也有一个类似的处理过程，根据CSS样式表达式生成一份CSSOM视图模式(CSS Object Model View)。DOM和CSSOM相结合后，就可以在屏幕上绘制一些像素点

浏览器的绘制过程是非常有趣的，在Chrome浏览器上，它把DOM树和CSS合并，然后使用一个2D图形库 Skia，对其进行光栅化处理。如果你玩过canvas元素的API，那么你将会对skia非常熟悉。skia不仅有一些类似于canvas API 中的moveTo和lineTo风格的方法，而且还包括一些更为先进的方法。事实上，所有需要被绘制的元素，skia将其称为一个集合，并且这个集合是可执行的，这个集合最终输出一串位图。这些位图会传给GPU，然后GPU会把这些所有的位图合成一张图片，最终输出到屏幕上。

![xx](http://gtms04.alicdn.com/tps/i4/T1lDVOFPJXXXayJZvx-900-396.jpeg)

因此skia的计算工作量将由我们的应用样式决定，应用在元素上的样式越复杂，skia计算也将越复杂，其工作量也将更大。如果你想更多的了解这方面的工作，可以看看 [Colt McAnlis](http://www.google.com/+ColtMcAnlis) 所写的 [article on how CSS affects page render weight](http://www.html5rocks.com/en/tutorials/speed/css-paint-times/)。

杯具的是，skia的这个绘制图形的工作是需要一定的时间来执行的，如果我们不想办法减少它们的绘图时间，计算量将会超过一帧16ms的时间，计算将会有丢针现象，给用户的感知是页面会有卡顿感。这种卡顿感在app上将更加明显，严重的影响了用户的体验。这不是我们想要的，所以让我们来看看什么的因素会使页面一直需要重绘，以及我们能过做些什么。

## 滚动

当浏览器上向上或者向下滚动时，在滚动区域未显示之前，浏览器需要重新绘制将要出现的区域。 通常情况下，这将是一个小的区域，但如果这块区域的DOM元素需要非常复杂的样式计算来绘制，那么就会导致一个非常小的区域不会像正常情况那样非常快速的绘制完成。

为了清楚的看到哪些区域在滚动时被绘制，可以使用Chrome浏览器的开发者调试工具，打开“Show Paint Rectangles”选项（在调试工具的右下角有一个设置的小图标）。打开了这个选项后，浏览网页时将会看到一闪一闪的红色长方形区域，这些区域就是浏览器正在绘制位图的区域。
![](http://gtms04.alicdn.com/tps/i4/T1ggNPFLpXXXcKnCHw-900-323.jpeg)

滚动性能对你的网站是至关重要的；当你的网站或者应用的滚动性能不好，用户很容易察觉，并会表示出不喜欢，甚至离开。因此，我们应该保证在页面滚动时保持轻量的重绘，以免用户感知到卡顿感。

如果你想知道更多关于滚动性能的细节，请参考这篇文章 [article on scrolling performance](http://www.html5rocks.com/en/tutorials/speed/scrolling/)。

## 交互行为

交互是另一个影响绘图的因素，如：hovers, clicks, touches, drags这些事件都会对绘图有影响。每当用户执行这些行为的其中一个，比如悬停，Chrome浏览器将不得不重新绘制受影响的元素。就像滚动一样，如果在发生hover, click, touch, drag等事件时，需要巨大而复杂的绘图计算，那么将使得帧速率明显下降。

每一个用户都希望看到酷的，平滑的交互动画，因此我们有必要在看看这些样式改变而执行的动画是否在大量消耗时间。

## 滚动和交互的组合

![](http://gtms01.alicdn.com/tps/i1/T13hdKFINbXXcclHgz-1147-556.jpeg)

如果我同时滚动和移动鼠标将会发生什么样的事呢？这时完全有可能在滚动的时候同时不经意间的与元素产生交互，使得触发更加昂贵的绘制。反过来，可以推算出它的运行帧已经远远超出了16.7ms (这个时间是根据用户感知推算出来的，一般每秒是60帧).这里有一个 demo 演示了我所说的意思。在这个例子中，你需要滚动和移动鼠标使得paint生效，并通过Chomes浏览器的开发者工具来查看。

![](http://gtms02.alicdn.com/tps/i2/T1cntNFNhXXXXQe32W-1369-797.png)Chrome 开发者工具展示的花费昂贵代价的画面

在上面这幅图中可以看到，当我把鼠标悬停到一个区域上时，开发者工具记录了绘制的过程。为了让演示明显，在这个例子中，动画使用的样式非常的笨重，因此我推断出，只有极个别的点通过了16ms的计算。最后要说的是，这样的绘制工作是不必要的，特别是发生在滚动期间，其他交互行为是完全不必要的。

因此我们要怎么阻止其在滚动期间发生其他交互呢？其实非常简单。只要监听滚动事件，在滚动时禁用hover效果，然后设置定时器，比如1s后使其重新加上hover效果。也就是保证当滚动的时候，我们不需要那些可能产生复杂绘制的交互。 然后预估一个安全的范围内重新开启这些效果。

<p class="tip">通过延迟生效交互效果来提升用户体验是非常明智的。但是你和你的团队保持好沟通，使得延迟操作在一个可接受的范围内，然后再次启用这些效果。</p>

这是该方法的代码片段:

### demo 3

<div>

</div>
<style>

</style>
<script>

</script>

```
// Used to track the enabling of hover effects
var enableTimer = 0;

/*
 * Listen for a scroll and use that to remove
 * the possibility of hover effects
 */
window.addEventListener('scroll', function() {
  clearTimeout(enableTimer);
  removeHoverClass();

  // enable after 1 second, choose your own value here!
  enableTimer = setTimeout(addHoverClass, 1000);
}, false);

/**
 * Removes the hover class from the body. Hover styles
 * are reliant on this class being present
 */
function removeHoverClass() {
  document.body.classList.remove('hover');
}

/**
 * Adds the hover class to the body. Hover styles
 * are reliant on this class being present
 */
function addHoverClass() {
  document.body.classList.add('hover');
}
```

正如侄子所描述的，我们使用一个class类来启用是否hover效果有效。所有的hover效果都写在这个hover类里：

<pre class="prettyprint">  
/* Expect the hover class to be on the body
 before doing any hover effects */
.hover .block:hover {
 …
}
</pre>

## 总结

渲染性能对用户体验来说是至关重要的，必须保证所有的绘制工作在16ms以内完成。你可以使用开发者工具来检测并定位可能出现的问题及找出其瓶颈，并解决和优化好。

无意的交互，特别是一些在元素上应用笨重的交互将是牺牲性能换来的，有时这种牺牲是致命。因此我们应该使用如上的代码方式来尽可能的避免。

骚年们，看看你的网站或者应用程序，是否有这样的问题，加以保护了吗?
