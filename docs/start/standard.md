## assets目录规范及webx配置

基于最低门槛、就近维护的原则，我们对业务级assets的维护制定一些简单的规范，并使用工具依据此规范进行处理和发布。

### 业务级assets的界定

* 不包含基础样式、通用组件代码；
* 纯业务功能、页面操作的代码；
* 能在页面间重用，但不是全站通用的样式和功能模块

### 规范

* 业务级代码垂直化在应用的SVN内；
* 在webx应用的目录 src/main/webapp/ 下，创建目录 assets/ ，用来存放业务级的css和js文件；
* assets/ 目录中的文件目录结构与文件名，尽量与 screen/ 目录中的vm文件路径及名称对应；
* 对应多个vm的assets文件，可以依据功能进行语义化命名。
* 举例：
juitem应用的 /juitem/item/src/main/webapp/ 目录结构为：

```
├── WEB-INF
│   ├ ...
├── assets
│   ├── admin
│   │   ├── check
│   │   │   ├── list_config.css
│   │   │   ├── list_config.js
│   │   │   └── ...
│   │   └── item
│   │       ├── item_detail.css
│   │       ├── item_detail.js
│   │       └── ...
│   └── ...
└── web
    └── templates
        ├── control
        │   ├── ...
        ├── layout
        │   ├── ...
        └── screen
            ├── admin
            │   ├── check
            │   │   ├── listConfigFirst.vm
            │   │   ├── listConfigSecond.vm
            │   │   └── ...
            │   └── item
            │       ├── itemDetail.vm
            │       └── ...
            │ ...

```

### 文件引用

* 本地调试、daily测试使用应用域名下的路径；
* 预发、线上环境，使用cdn路径。

### webx配置

* 在 dev.properties(/juitem/item/dev.properties) 文件中增加垂直化assets路径配置项：
  * 本地及daily：

```
  juitem.vertical.assets.host=item.juadmin.daily.taobao.net
```
  * 预发及线上：

```
  juitem.vertical.assets.host=g.tbcdn.cn/ju/jad-juadmin
```
* 在 uris.xml(/juitem/item/src/main/webapp/WEB-INF/common/uris.xml) 增加变量：

```
<content-uri id="juitemVerticalAssetsServer" exposed="true">
    <serverURI>http://${juitem.vertical.assets.host}:${juitem.gitcdn.port}</serverURI>
</content-uri>
```
* vm文件中引用assets：

```
<link rel="stylesheet" href="${juitemVerticalAssetsServer}/assets/admin/check/list_config.css">
<script src="${juitemVerticalAssetsServer}/assets/admin/check/list_config.js"></script>

```
