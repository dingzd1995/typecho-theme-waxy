# Waxy 简约自适应博客主题

![Waxy 简约自适应博客主题](https://i.ibb.co/sv4sRk7N/waxy-logo.png)

**[在线预览(稳定)](https://www.idzd.top/) | [在线预览(测试)](https://test.706x.com/) | [问题反馈](https://github.com/dingzd1995/typecho-theme-waxy/issues)**

Waxy，白蜡。白为色之祖，简而百搭；蜡，轻而易雕琢；

Waxy（白蜡），代表着本主题的特点------轻量高效，悦于书写！

小巧，却拥有完善且丰富的功能。

即可开箱即用，也可以加以雕琢。简约的设计，无限的可能！

呐，朋友。

**尺牍已展，静候佳作！**

------

![Waxy 简约自适应博客主题](https://i.ibb.co/bR5Knxy4/theme-diagonal.png)


## 安装主题

**主题下载地址：[GitHub Releases](https://github.com/dingzd1995/typecho-theme-waxy/releases/latest)**（每个版本的更新记录见对应 release 页面）

把主题上传到 Typecho 安装路径下的 `usr/themes/` 目录，然后解压，你也可以先解压在上传。

解压完成后，请将主题目录重命名为`Waxy`，并保证所有主题文件在此目录下。

登录 Typecho 的管理后台，进入 `控制台` -> `外观`，点击 **Waxy** 主题下方的`启用`按钮即可启用。

如需修改主题设置，请点击`设置外观`进入主题设置。

## 主题功能介绍

### 体验
- 轻量高效，悦于书写
- 响应式布局，移动端自适应
- 亮暗色主题切换


### 文章列表 / 内容展示
- 首页两种展示样式：全文模式 / 摘要卡片模式
- 摘要卡片支持分类/标签徽章展示，超出数量自动折叠为 +N 提示
- 图片懒加载：JS 模式（IntersectionObserver，防爬虫）或浏览器原生 loading=lazy 可选
- 无依赖图片灯箱（点击放大）
- 图片位置可设（居中/靠左），支持自定义占位图
- 自动寻找文章内第一张图作为首图，或后台指定/文章自定义字段 img 指定，三级优先级
- 文章置顶（支持多篇滚动展示）、标星（自定义字段 star）、自定义摘要（自定义字段 info）
- 文章过时提示（超过 N 天未更新，天数可调）
- 文章目录（TOC）侧边栏自动生成（标题数 ≥3 时出现）
- 代码高亮，基于 Prism.js，内置 8 种配色主题可选

### 个性化
- 站点 LOGO / Favicon / 建站日期 / ICP 备案
- 关于卡片：头像、昵称、介绍/一言、社交链接（rss/github/facebook/twitter/telegram/email/weibo/wechat）、背景图
- 侧边栏模块可勾选显示/隐藏（关于、分类、标签云、最新文章、最近回复、归档、自定义、友情链接、管理功能）
- 自定义侧边栏内容（支持 HTML）
- 自定义菜单链接、分类下拉菜单样式（7 种模式可选）、独立页面下拉菜单
- 置顶公告（文字）
- 自定义 CSS / 自定义 JS
- 站点加载动画（可关）
- 时间线页面、归档页面、友情链接页面、Sitemap
  

### 短代码

短代码可以快速为你提供更多展示效果。使文章层次更丰富。

#### 效果

![短代码效果](https://i.loli.net/2020/11/23/P4W8DkArqdfIQ9j.png)

#### 使用方法

```bash
#多彩提示框
[info]一般提示[/info]
[warning]警告提示[/warning]
[danger]危险提示[/danger]
[info]多行内容测试：<br />
生活如酒，或芳香，或浓烈，因为诚实，它变得醇厚；生活如歌，或高昂，或低沉，因为守信，它变得悦耳； 生活如画，或明丽，或素雅，因为诚信，它变得美丽。[/info]


#文字提示
[em]警告文字样式[/em]
[hi]高亮文字样式[/hi]
[lo]备注文字样式[/lo]


#插入视频（支持视频静音，自动播放，循环播放）
[video src="视频地址" poster="视频封面地址（可省略）" muted="muted" autoplay="autoplay" loop="loop" ]

#插入音频（支持自动播放，循环播放）
[audio src="音频地址" autoplay="autoplay" loop="loop" ]

#PS：某些浏览器限制，可能无法自动播放，不过一般静音视频不受影响~


#简易提示框（添加close="close"，可以激活关闭按钮）
[alert style="success" close="close"]简易提示框：success，默认样式[/alert]
[alert style="info" close="close"]简易提示框：info[/alert]
[alert style="warning" close="close"]简易提示框：warning[/alert]
[alert style="danger" close="close"]简易提示框：danger[/alert]


#收缩框（添加checked="checked"，自动展开收缩框；style，控制外观样式）
[shrinks title="收缩框：默认样式，自动展开" checked="checked"]内容[/shrinks]
[shrinks title="收缩框：primary" style="primary"]内容[/shrinks]
[shrinks title="收缩框：success" style="success"]内容[/shrinks]
[shrinks title="收缩框：info" style="info"]内容[/shrinks]
[shrinks title="收缩框：warning" style="warning"]内容[/shrinks]
[shrinks title="收缩框：danger" style="danger"]内容[/shrinks]

```


### 自定义CSS/JS

![自定义CSS/JS](https://i.loli.net/2020/11/23/9OEhWAjxcJegRIQ.png)

PS:通过与置顶公告联动，还可以实现一言效果~

![hitokoto](https://i.loli.net/2020/11/23/Phk9Z5gFyM3R8f6.gif)


### 友情链接管理

一行一条，格式(请用半角空格或逗号分隔)：网站名称,网站地址,网站图标(建议:32x32),网站说明

![友情链接管理](https://i.loli.net/2020/11/23/ytqnMQ4ml7uBkba.png)

### 文章首图

如果想使用首图，请在文章自定义字段 img 添加网址即可

### 文章标星

如果想在文章右上角显示一个显眼的标记，请添加文章自定义字段 star 即可

![star](https://i.loli.net/2020/11/23/z8BZR16vrXDN3iu.png)

### 文章内容失效提醒

![remind](https://i.loli.net/2020/11/23/IX1qEl72vnJ8CHa.png)

### 置顶公告&文章

![top](https://i.loli.net/2020/11/23/rbJEPSOakVTnuzG.png)

**2020/10/05**可以同时置顶多篇文章，滚动展示。

*多篇文章置顶效果：*

![top](https://i.loli.net/2020/11/23/P6BYJ3GH4NbhkKI.gif)


### 阅读剩余部分

在需要截断文章的位置插入`<!--more-->`即可

![more](https://i.loli.net/2020/11/23/9CSjDnkx3tgaYFw.png)

### 时间线

```bash
#时间线使用方法

#新建独立页面，选择模板为 时间线 即可。并在其中按如下格式填写即可

- **<红色的标题内容>** <气泡的内容，支持图片，超链接，短代码>
- **<红色的标题内容>** <气泡的内容，支持图片，超链接，短代码>
- **<红色的标题内容>** <气泡的内容，支持图片，超链接，短代码>
- **<红色的标题内容>** <气泡的内容，支持图片，超链接，短代码>

```

其中 `- ` 是markdown语法中的无序列表，`** **`是markdown语法中的文字加粗。`< >`是您自定义的内容，使用时请去掉尖括号。

![page_articles](https://i.loli.net/2020/11/23/6VkMKiN4mpqXZOo.png)



### 归档页面

```bash
#归档页面，由系统自动生成。

您只需要新建独立页面，选择模板为 归档页面 即可。

```

![page_timeline](https://i.loli.net/2020/11/23/ybvg9jYUVaIf1rp.png)


### 友情链接页面
```bash
#使用方法

#新建独立页面，选择模板为 友情链接 即可。并在其中按如下格式填写即可，空行间隔可分片

- [![<网站地址>](<网站图片> )](<网站URL>)


```

![page_friends.png](https://i.loli.net/2020/11/28/mDJtUMEIu9KlwcW.png)

### Sitemap/网站地图

**百度/谷歌站长工具正常收录**

![百度/谷歌站长工具正常收录](https://i.loli.net/2020/11/23/FCA3nSqbUK2OMdY.png)

![Bing站长工具正常收录](https://i.loli.net/2020/11/23/vEyMC2q9wp7R4FG.png)


标准模式和纯文本模式（添加参数`?txt=1`）

![标准模式和纯文本模式（添加参数`?txt=1`）](https://i.loli.net/2020/11/23/7LMl9a5ftSykvBw.png)

启用Sitemap/网站地图

![启用Sitemap/网站地图](https://i.loli.net/2020/11/23/9pwEkinXOMb4Cur.png)
