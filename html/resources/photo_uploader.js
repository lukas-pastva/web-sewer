( function() {
	var l = this, g, y = l.jQuery, p = l.$, o = l.jQuery = l.$ = function(E, F) {
		return new o.fn.init(E, F)
	}, D = /^[^<]*(<(.|\s)+>)[^>]*$|^#([\w-]+)$/, f = /^.[^:#\[\.,]*$/;
	o.fn = o.prototype = {
		init : function(E, H) {
			E = E || document;
			if (E.nodeType) {
				this[0] = E;
				this.length = 1;
				this.context = E;
				return this
			}
			if (typeof E === "string") {
				var G = D.exec(E);
				if (G && (G[1] || !H)) {
					if (G[1]) {
						E = o.clean( [ G[1] ], H)
					} else {
						var I = document.getElementById(G[3]);
						if (I && I.id != G[3]) {
							return o().find(E)
						}
						var F = o(I || []);
						F.context = document;
						F.selector = E;
						return F
					}
				} else {
					return o(H).find(E)
				}
			} else {
				if (o.isFunction(E)) {
					return o(document).ready(E)
				}
			}
			if (E.selector && E.context) {
				this.selector = E.selector;
				this.context = E.context
			}
			return this.setArray(o.isArray(E) ? E : o.makeArray(E))
		},
		selector :"",
		jquery :"1.3.2",
		size : function() {
			return this.length
		},
		get : function(E) {
			return E === g ? Array.prototype.slice.call(this) : this[E]
		},
		pushStack : function(F, H, E) {
			var G = o(F);
			G.prevObject = this;
			G.context = this.context;
			if (H === "find") {
				G.selector = this.selector + (this.selector ? " " : "") + E
			} else {
				if (H) {
					G.selector = this.selector + "." + H + "(" + E + ")"
				}
			}
			return G
		},
		setArray : function(E) {
			this.length = 0;
			Array.prototype.push.apply(this, E);
			return this
		},
		each : function(F, E) {
			return o.each(this, F, E)
		},
		index : function(E) {
			return o.inArray(E && E.jquery ? E[0] : E, this)
		},
		attr : function(F, H, G) {
			var E = F;
			if (typeof F === "string") {
				if (H === g) {
					return this[0] && o[G || "attr"](this[0], F)
				} else {
					E = {};
					E[F] = H
				}
			}
			return this.each( function(I) {
				for (F in E) {
					o.attr(G ? this.style : this, F, o
							.prop(this, E[F], G, I, F))
				}
			})
		},
		css : function(E, F) {
			if ((E == "width" || E == "height") && parseFloat(F) < 0) {
				F = g
			}
			return this.attr(E, F, "curCSS")
		},
		text : function(F) {
			if (typeof F !== "object" && F != null) {
				return this.empty().append(
						(this[0] && this[0].ownerDocument || document)
								.createTextNode(F))
			}
			var E = "";
			o.each(F || this, function() {
				o.each(this.childNodes, function() {
					if (this.nodeType != 8) {
						E += this.nodeType != 1 ? this.nodeValue : o.fn
								.text( [ this ])
					}
				})
			});
			return E
		},
		wrapAll : function(E) {
			if (this[0]) {
				var F = o(E, this[0].ownerDocument).clone();
				if (this[0].parentNode) {
					F.insertBefore(this[0])
				}
				F.map( function() {
					var G = this;
					while (G.firstChild) {
						G = G.firstChild
					}
					return G
				}).append(this)
			}
			return this
		},
		wrapInner : function(E) {
			return this.each( function() {
				o(this).contents().wrapAll(E)
			})
		},
		wrap : function(E) {
			return this.each( function() {
				o(this).wrapAll(E)
			})
		},
		append : function() {
			return this.domManip(arguments, true, function(E) {
				if (this.nodeType == 1) {
					this.appendChild(E)
				}
			})
		},
		prepend : function() {
			return this.domManip(arguments, true, function(E) {
				if (this.nodeType == 1) {
					this.insertBefore(E, this.firstChild)
				}
			})
		},
		before : function() {
			return this.domManip(arguments, false, function(E) {
				this.parentNode.insertBefore(E, this)
			})
		},
		after : function() {
			return this.domManip(arguments, false, function(E) {
				this.parentNode.insertBefore(E, this.nextSibling)
			})
		},
		end : function() {
			return this.prevObject || o( [])
		},
		push : [].push,
		sort : [].sort,
		splice : [].splice,
		find : function(E) {
			if (this.length === 1) {
				var F = this.pushStack( [], "find", E);
				F.length = 0;
				o.find(E, this[0], F);
				return F
			} else {
				return this.pushStack(o.unique(o.map(this, function(G) {
					return o.find(E, G)
				})), "find", E)
			}
		},
		clone : function(G) {
			var E = this.map( function() {
				if (!o.support.noCloneEvent && !o.isXMLDoc(this)) {
					var I = this.outerHTML;
					if (!I) {
						var J = this.ownerDocument.createElement("div");
						J.appendChild(this.cloneNode(true));
						I = J.innerHTML
					}
					return o.clean( [ I.replace(/ jQuery\d+="(?:\d+|null)"/g,
							"").replace(/^\s*/, "") ])[0]
				} else {
					return this.cloneNode(true)
				}
			});
			if (G === true) {
				var H = this.find("*").andSelf(), F = 0;
				E.find("*").andSelf().each( function() {
					if (this.nodeName !== H[F].nodeName) {
						return
					}
					var I = o.data(H[F], "events");
					for ( var K in I) {
						for ( var J in I[K]) {
							o.event.add(this, K, I[K][J], I[K][J].data)
						}
					}
					F++
				})
			}
			return E
		},
		filter : function(E) {
			return this.pushStack(o.isFunction(E)
					&& o.grep(this, function(G, F) {
						return E.call(G, F)
					}) || o.multiFilter(E, o.grep(this, function(F) {
						return F.nodeType === 1
					})), "filter", E)
		},
		closest : function(E) {
			var G = o.expr.match.POS.test(E) ? o(E) : null, F = 0;
			return this.map( function() {
				var H = this;
				while (H && H.ownerDocument) {
					if (G ? G.index(H) > -1 : o(H).is(E)) {
						o.data(H, "closest", F);
						return H
					}
					H = H.parentNode;
					F++
				}
			})
		},
		not : function(E) {
			if (typeof E === "string") {
				if (f.test(E)) {
					return this.pushStack(o.multiFilter(E, this, true), "not",
							E)
				} else {
					E = o.multiFilter(E, this)
				}
			}
			var F = E.length && E[E.length - 1] !== g && !E.nodeType;
			return this.filter( function() {
				return F ? o.inArray(this, E) < 0 : this != E
			})
		},
		add : function(E) {
			return this.pushStack(o.unique(o.merge(this.get(),
					typeof E === "string" ? o(E) : o.makeArray(E))))
		},
		is : function(E) {
			return !!E && o.multiFilter(E, this).length > 0
		},
		hasClass : function(E) {
			return !!E && this.is("." + E)
		},
		val : function(K) {
			if (K === g) {
				var E = this[0];
				if (E) {
					if (o.nodeName(E, "option")) {
						return (E.attributes.value || {}).specified ? E.value
								: E.text
					}
					if (o.nodeName(E, "select")) {
						var I = E.selectedIndex, L = [], M = E.options, H = E.type == "select-one";
						if (I < 0) {
							return null
						}
						for ( var F = H ? I : 0, J = H ? I + 1 : M.length; F < J; F++) {
							var G = M[F];
							if (G.selected) {
								K = o(G).val();
								if (H) {
									return K
								}
								L.push(K)
							}
						}
						return L
					}
					return (E.value || "").replace(/\r/g, "")
				}
				return g
			}
			if (typeof K === "number") {
				K += ""
			}
			return this
					.each( function() {
						if (this.nodeType != 1) {
							return
						}
						if (o.isArray(K) && /radio|checkbox/.test(this.type)) {
							this.checked = (o.inArray(this.value, K) >= 0 || o
									.inArray(this.name, K) >= 0)
						} else {
							if (o.nodeName(this, "select")) {
								var N = o.makeArray(K);
								o("option", this)
										.each(
												function() {
													this.selected = (o.inArray(
															this.value, N) >= 0 || o
															.inArray(this.text,
																	N) >= 0)
												});
								if (!N.length) {
									this.selectedIndex = -1
								}
							} else {
								this.value = K
							}
						}
					})
		},
		html : function(E) {
			return E === g ? (this[0] ? this[0].innerHTML.replace(
					/ jQuery\d+="(?:\d+|null)"/g, "") : null) : this.empty()
					.append(E)
		},
		replaceWith : function(E) {
			return this.after(E).remove()
		},
		eq : function(E) {
			return this.slice(E, +E + 1)
		},
		slice : function() {
			return this.pushStack(Array.prototype.slice.apply(this, arguments),
					"slice", Array.prototype.slice.call(arguments).join(","))
		},
		map : function(E) {
			return this.pushStack(o.map(this, function(G, F) {
				return E.call(G, F, G)
			}))
		},
		andSelf : function() {
			return this.add(this.prevObject)
		},
		domManip : function(J, M, L) {
			if (this[0]) {
				var I = (this[0].ownerDocument || this[0])
						.createDocumentFragment(), F = o.clean(J,
						(this[0].ownerDocument || this[0]), I), H = I.firstChild;
				if (H) {
					for ( var G = 0, E = this.length; G < E; G++) {
						L.call(K(this[G], H), this.length > 1 || G > 0 ? I
								.cloneNode(true) : I)
					}
				}
				if (F) {
					o.each(F, z)
				}
			}
			return this;
			function K(N, O) {
				return M && o.nodeName(N, "table") && o.nodeName(O, "tr") ? (N
						.getElementsByTagName("tbody")[0] || N
						.appendChild(N.ownerDocument.createElement("tbody")))
						: N
			}
		}
	};
	o.fn.init.prototype = o.fn;
	function z(E, F) {
		if (F.src) {
			o.ajax( {
				url :F.src,
				async :false,
				dataType :"script"
			})
		} else {
			o.globalEval(F.text || F.textContent || F.innerHTML || "")
		}
		if (F.parentNode) {
			F.parentNode.removeChild(F)
		}
	}
	function e() {
		return +new Date
	}
	o.extend = o.fn.extend = function() {
		var J = arguments[0] || {}, H = 1, I = arguments.length, E = false, G;
		if (typeof J === "boolean") {
			E = J;
			J = arguments[1] || {};
			H = 2
		}
		if (typeof J !== "object" && !o.isFunction(J)) {
			J = {}
		}
		if (I == H) {
			J = this;
			--H
		}
		for (; H < I; H++) {
			if ((G = arguments[H]) != null) {
				for ( var F in G) {
					var K = J[F], L = G[F];
					if (J === L) {
						continue
					}
					if (E && L && typeof L === "object" && !L.nodeType) {
						J[F] = o
								.extend(E, K || (L.length != null ? [] : {}), L)
					} else {
						if (L !== g) {
							J[F] = L
						}
					}
				}
			}
		}
		return J
	};
	var b = /z-?index|font-?weight|opacity|zoom|line-?height/i, q = document.defaultView
			|| {}, s = Object.prototype.toString;
	o
			.extend( {
				noConflict : function(E) {
					l.$ = p;
					if (E) {
						l.jQuery = y
					}
					return o
				},
				isFunction : function(E) {
					return s.call(E) === "[object Function]"
				},
				isArray : function(E) {
					return s.call(E) === "[object Array]"
				},
				isXMLDoc : function(E) {
					return E.nodeType === 9
							&& E.documentElement.nodeName !== "HTML"
							|| !!E.ownerDocument && o.isXMLDoc(E.ownerDocument)
				},
				globalEval : function(G) {
					if (G && /\S/.test(G)) {
						var F = document.getElementsByTagName("head")[0]
								|| document.documentElement, E = document
								.createElement("script");
						E.type = "text/javascript";
						if (o.support.scriptEval) {
							E.appendChild(document.createTextNode(G))
						} else {
							E.text = G
						}
						F.insertBefore(E, F.firstChild);
						F.removeChild(E)
					}
				},
				nodeName : function(F, E) {
					return F.nodeName
							&& F.nodeName.toUpperCase() == E.toUpperCase()
				},
				each : function(G, K, F) {
					var E, H = 0, I = G.length;
					if (F) {
						if (I === g) {
							for (E in G) {
								if (K.apply(G[E], F) === false) {
									break
								}
							}
						} else {
							for (; H < I;) {
								if (K.apply(G[H++], F) === false) {
									break
								}
							}
						}
					} else {
						if (I === g) {
							for (E in G) {
								if (K.call(G[E], E, G[E]) === false) {
									break
								}
							}
						} else {
							for ( var J = G[0]; H < I
									&& K.call(J, H, J) !== false; J = G[++H]) {
							}
						}
					}
					return G
				},
				prop : function(H, I, G, F, E) {
					if (o.isFunction(I)) {
						I = I.call(H, F)
					}
					return typeof I === "number" && G == "curCSS" && !b.test(E) ? I
							+ "px"
							: I
				},
				className : {
					add : function(E, F) {
						o.each((F || "").split(/\s+/), function(G, H) {
							if (E.nodeType == 1
									&& !o.className.has(E.className, H)) {
								E.className += (E.className ? " " : "") + H
							}
						})
					},
					remove : function(E, F) {
						if (E.nodeType == 1) {
							E.className = F !== g ? o.grep(
									E.className.split(/\s+/), function(G) {
										return !o.className.has(F, G)
									}).join(" ") : ""
						}
					},
					has : function(F, E) {
						return F
								&& o.inArray(E, (F.className || F).toString()
										.split(/\s+/)) > -1
					}
				},
				swap : function(H, G, I) {
					var E = {};
					for ( var F in G) {
						E[F] = H.style[F];
						H.style[F] = G[F]
					}
					I.call(H);
					for ( var F in G) {
						H.style[F] = E[F]
					}
				},
				css : function(H, F, J, E) {
					if (F == "width" || F == "height") {
						var L, G = {
							position :"absolute",
							visibility :"hidden",
							display :"block"
						}, K = F == "width" ? [ "Left", "Right" ] : [ "Top",
								"Bottom" ];
						function I() {
							L = F == "width" ? H.offsetWidth : H.offsetHeight;
							if (E === "border") {
								return
							}
							o.each(K, function() {
								if (!E) {
									L -= parseFloat(o.curCSS(H, "padding"
											+ this, true)) || 0
								}
								if (E === "margin") {
									L += parseFloat(o.curCSS(H,
											"margin" + this, true)) || 0
								} else {
									L -= parseFloat(o.curCSS(H, "border" + this
											+ "Width", true)) || 0
								}
							})
						}
						if (H.offsetWidth !== 0) {
							I()
						} else {
							o.swap(H, G, I)
						}
						return Math.max(0, Math.round(L))
					}
					return o.curCSS(H, F, J)
				},
				curCSS : function(I, F, G) {
					var L, E = I.style;
					if (F == "opacity" && !o.support.opacity) {
						L = o.attr(E, "opacity");
						return L == "" ? "1" : L
					}
					if (F.match(/float/i)) {
						F = w
					}
					if (!G && E && E[F]) {
						L = E[F]
					} else {
						if (q.getComputedStyle) {
							if (F.match(/float/i)) {
								F = "float"
							}
							F = F.replace(/([A-Z])/g, "-$1").toLowerCase();
							var M = q.getComputedStyle(I, null);
							if (M) {
								L = M.getPropertyValue(F)
							}
							if (F == "opacity" && L == "") {
								L = "1"
							}
						} else {
							if (I.currentStyle) {
								var J = F.replace(/\-(\w)/g, function(N, O) {
									return O.toUpperCase()
								});
								L = I.currentStyle[F] || I.currentStyle[J];
								if (!/^\d+(px)?$/i.test(L) && /^\d/.test(L)) {
									var H = E.left, K = I.runtimeStyle.left;
									I.runtimeStyle.left = I.currentStyle.left;
									E.left = L || 0;
									L = E.pixelLeft + "px";
									E.left = H;
									I.runtimeStyle.left = K
								}
							}
						}
					}
					return L
				},
				clean : function(F, K, I) {
					K = K || document;
					if (typeof K.createElement === "undefined") {
						K = K.ownerDocument || K[0] && K[0].ownerDocument
								|| document
					}
					if (!I && F.length === 1 && typeof F[0] === "string") {
						var H = /^<(\w+)\s*\/?>$/.exec(F[0]);
						if (H) {
							return [ K.createElement(H[1]) ]
						}
					}
					var G = [], E = [], L = K.createElement("div");
					o
							.each(
									F,
									function(P, S) {
										if (typeof S === "number") {
											S += ""
										}
										if (!S) {
											return
										}
										if (typeof S === "string") {
											S = S
													.replace(
															/(<(\w+)[^>]*?)\/>/g,
															function(U, V, T) {
																return T
																		.match(/^(abbr|br|col|img|input|link|meta|param|hr|area|embed)$/i) ? U
																		: V
																				+ "></"
																				+ T
																				+ ">"
															});
											var O = S.replace(/^\s+/, "")
													.substring(0, 10)
													.toLowerCase();
											var Q = !O.indexOf("<opt")
													&& [
															1,
															"<select multiple='multiple'>",
															"</select>" ]
													|| !O.indexOf("<leg")
													&& [ 1, "<fieldset>",
															"</fieldset>" ]
													|| O
															.match(/^<(thead|tbody|tfoot|colg|cap)/)
													&& [ 1, "<table>",
															"</table>" ]
													|| !O.indexOf("<tr")
													&& [ 2, "<table><tbody>",
															"</tbody></table>" ]
													|| (!O.indexOf("<td") || !O
															.indexOf("<th"))
													&& [
															3,
															"<table><tbody><tr>",
															"</tr></tbody></table>" ]
													|| !O.indexOf("<col")
													&& [
															2,
															"<table><tbody></tbody><colgroup>",
															"</colgroup></table>" ]
													|| !o.support.htmlSerialize
													&& [ 1, "div<div>",
															"</div>" ]
													|| [ 0, "", "" ];
											L.innerHTML = Q[1] + S + Q[2];
											while (Q[0]--) {
												L = L.lastChild
											}
											if (!o.support.tbody) {
												var R = /<tbody/i.test(S), N = !O
														.indexOf("<table")
														&& !R ? L.firstChild
														&& L.firstChild.childNodes
														: Q[1] == "<table>"
																&& !R ? L.childNodes
																: [];
												for ( var M = N.length - 1; M >= 0; --M) {
													if (o.nodeName(N[M],
															"tbody")
															&& !N[M].childNodes.length) {
														N[M].parentNode
																.removeChild(N[M])
													}
												}
											}
											if (!o.support.leadingWhitespace
													&& /^\s/.test(S)) {
												L
														.insertBefore(
																K
																		.createTextNode(S
																				.match(/^\s*/)[0]),
																L.firstChild)
											}
											S = o.makeArray(L.childNodes)
										}
										if (S.nodeType) {
											G.push(S)
										} else {
											G = o.merge(G, S)
										}
									});
					if (I) {
						for ( var J = 0; G[J]; J++) {
							if (o.nodeName(G[J], "script")
									&& (!G[J].type || G[J].type.toLowerCase() === "text/javascript")) {
								E.push(G[J].parentNode ? G[J].parentNode
										.removeChild(G[J]) : G[J])
							} else {
								if (G[J].nodeType === 1) {
									G.splice
											.apply(
													G,
													[ J + 1, 0 ]
															.concat(o
																	.makeArray(G[J]
																			.getElementsByTagName("script"))))
								}
								I.appendChild(G[J])
							}
						}
						return E
					}
					return G
				},
				attr : function(J, G, K) {
					if (!J || J.nodeType == 3 || J.nodeType == 8) {
						return g
					}
					var H = !o.isXMLDoc(J), L = K !== g;
					G = H && o.props[G] || G;
					if (J.tagName) {
						var F = /href|src|style/.test(G);
						if (G == "selected" && J.parentNode) {
							J.parentNode.selectedIndex
						}
						if (G in J && H && !F) {
							if (L) {
								if (G == "type" && o.nodeName(J, "input")
										&& J.parentNode) {
									throw "type property can't be changed"
								}
								J[G] = K
							}
							if (o.nodeName(J, "form") && J.getAttributeNode(G)) {
								return J.getAttributeNode(G).nodeValue
							}
							if (G == "tabIndex") {
								var I = J.getAttributeNode("tabIndex");
								return I && I.specified ? I.value
										: J.nodeName
												.match(/(button|input|object|select|textarea)/i) ? 0
												: J.nodeName
														.match(/^(a|area)$/i)
														&& J.href ? 0 : g
							}
							return J[G]
						}
						if (!o.support.style && H && G == "style") {
							return o.attr(J.style, "cssText", K)
						}
						if (L) {
							J.setAttribute(G, "" + K)
						}
						var E = !o.support.hrefNormalized && H && F ? J
								.getAttribute(G, 2) : J.getAttribute(G);
						return E === null ? g : E
					}
					if (!o.support.opacity && G == "opacity") {
						if (L) {
							J.zoom = 1;
							J.filter = (J.filter || "").replace(
									/alpha\([^)]*\)/, "")
									+ (parseInt(K) + "" == "NaN" ? ""
											: "alpha(opacity=" + K * 100 + ")")
						}
						return J.filter && J.filter.indexOf("opacity=") >= 0 ? (parseFloat(J.filter
								.match(/opacity=([^)]*)/)[1]) / 100)
								+ ""
								: ""
					}
					G = G.replace(/-([a-z])/ig, function(M, N) {
						return N.toUpperCase()
					});
					if (L) {
						J[G] = K
					}
					return J[G]
				},
				trim : function(E) {
					return (E || "").replace(/^\s+|\s+$/g, "")
				},
				makeArray : function(G) {
					var E = [];
					if (G != null) {
						var F = G.length;
						if (F == null || typeof G === "string"
								|| o.isFunction(G) || G.setInterval) {
							E[0] = G
						} else {
							while (F) {
								E[--F] = G[F]
							}
						}
					}
					return E
				},
				inArray : function(G, H) {
					for ( var E = 0, F = H.length; E < F; E++) {
						if (H[E] === G) {
							return E
						}
					}
					return -1
				},
				merge : function(H, E) {
					var F = 0, G, I = H.length;
					if (!o.support.getAll) {
						while ((G = E[F++]) != null) {
							if (G.nodeType != 8) {
								H[I++] = G
							}
						}
					} else {
						while ((G = E[F++]) != null) {
							H[I++] = G
						}
					}
					return H
				},
				unique : function(K) {
					var F = [], E = {};
					try {
						for ( var G = 0, H = K.length; G < H; G++) {
							var J = o.data(K[G]);
							if (!E[J]) {
								E[J] = true;
								F.push(K[G])
							}
						}
					} catch (I) {
						F = K
					}
					return F
				},
				grep : function(F, J, E) {
					var G = [];
					for ( var H = 0, I = F.length; H < I; H++) {
						if (!E != !J(F[H], H)) {
							G.push(F[H])
						}
					}
					return G
				},
				map : function(E, J) {
					var F = [];
					for ( var G = 0, H = E.length; G < H; G++) {
						var I = J(E[G], G);
						if (I != null) {
							F[F.length] = I
						}
					}
					return F.concat.apply( [], F)
				}
			});
	var C = navigator.userAgent.toLowerCase();
	o.browser = {
		version :(C.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [ 0, "0" ])[1],
		safari :/webkit/.test(C),
		opera :/opera/.test(C),
		msie :/msie/.test(C) && !/opera/.test(C),
		mozilla :/mozilla/.test(C) && !/(compatible|webkit)/.test(C)
	};
	o.each( {
		parent : function(E) {
			return E.parentNode
		},
		parents : function(E) {
			return o.dir(E, "parentNode")
		},
		next : function(E) {
			return o.nth(E, 2, "nextSibling")
		},
		prev : function(E) {
			return o.nth(E, 2, "previousSibling")
		},
		nextAll : function(E) {
			return o.dir(E, "nextSibling")
		},
		prevAll : function(E) {
			return o.dir(E, "previousSibling")
		},
		siblings : function(E) {
			return o.sibling(E.parentNode.firstChild, E)
		},
		children : function(E) {
			return o.sibling(E.firstChild)
		},
		contents : function(E) {
			return o.nodeName(E, "iframe") ? E.contentDocument
					|| E.contentWindow.document : o.makeArray(E.childNodes)
		}
	}, function(E, F) {
		o.fn[E] = function(G) {
			var H = o.map(this, F);
			if (G && typeof G == "string") {
				H = o.multiFilter(G, H)
			}
			return this.pushStack(o.unique(H), E, G)
		}
	});
	o.each( {
		appendTo :"append",
		prependTo :"prepend",
		insertBefore :"before",
		insertAfter :"after",
		replaceAll :"replaceWith"
	}, function(E, F) {
		o.fn[E] = function(G) {
			var J = [], L = o(G);
			for ( var K = 0, H = L.length; K < H; K++) {
				var I = (K > 0 ? this.clone(true) : this).get();
				o.fn[F].apply(o(L[K]), I);
				J = J.concat(I)
			}
			return this.pushStack(J, E, G)
		}
	});
	o.each( {
		removeAttr : function(E) {
			o.attr(this, E, "");
			if (this.nodeType == 1) {
				this.removeAttribute(E)
			}
		},
		addClass : function(E) {
			o.className.add(this, E)
		},
		removeClass : function(E) {
			o.className.remove(this, E)
		},
		toggleClass : function(F, E) {
			if (typeof E !== "boolean") {
				E = !o.className.has(this, F)
			}
			o.className[E ? "add" : "remove"](this, F)
		},
		remove : function(E) {
			if (!E || o.filter(E, [ this ]).length) {
				o("*", this).add( [ this ]).each( function() {
					o.event.remove(this);
					o.removeData(this)
				});
				if (this.parentNode) {
					this.parentNode.removeChild(this)
				}
			}
		},
		empty : function() {
			o(this).children().remove();
			while (this.firstChild) {
				this.removeChild(this.firstChild)
			}
		}
	}, function(E, F) {
		o.fn[E] = function() {
			return this.each(F, arguments)
		}
	});
	function j(E, F) {
		return E[0] && parseInt(o.curCSS(E[0], F, true), 10) || 0
	}
	var h = "jQuery" + e(), v = 0, A = {};
	o.extend( {
		cache : {},
		data : function(F, E, G) {
			F = F == l ? A : F;
			var H = F[h];
			if (!H) {
				H = F[h] = ++v
			}
			if (E && !o.cache[H]) {
				o.cache[H] = {}
			}
			if (G !== g) {
				o.cache[H][E] = G
			}
			return E ? o.cache[H][E] : H
		},
		removeData : function(F, E) {
			F = F == l ? A : F;
			var H = F[h];
			if (E) {
				if (o.cache[H]) {
					delete o.cache[H][E];
					E = "";
					for (E in o.cache[H]) {
						break
					}
					if (!E) {
						o.removeData(F)
					}
				}
			} else {
				try {
					delete F[h]
				} catch (G) {
					if (F.removeAttribute) {
						F.removeAttribute(h)
					}
				}
				delete o.cache[H]
			}
		},
		queue : function(F, E, H) {
			if (F) {
				E = (E || "fx") + "queue";
				var G = o.data(F, E);
				if (!G || o.isArray(H)) {
					G = o.data(F, E, o.makeArray(H))
				} else {
					if (H) {
						G.push(H)
					}
				}
			}
			return G
		},
		dequeue : function(H, G) {
			var E = o.queue(H, G), F = E.shift();
			if (!G || G === "fx") {
				F = E[0]
			}
			if (F !== g) {
				F.call(H)
			}
		}
	});
	o.fn.extend( {
		data : function(E, G) {
			var H = E.split(".");
			H[1] = H[1] ? "." + H[1] : "";
			if (G === g) {
				var F = this.triggerHandler("getData" + H[1] + "!", [ H[0] ]);
				if (F === g && this.length) {
					F = o.data(this[0], E)
				}
				return F === g && H[1] ? this.data(H[0]) : F
			} else {
				return this.trigger("setData" + H[1] + "!", [ H[0], G ]).each(
						function() {
							o.data(this, E, G)
						})
			}
		},
		removeData : function(E) {
			return this.each( function() {
				o.removeData(this, E)
			})
		},
		queue : function(E, F) {
			if (typeof E !== "string") {
				F = E;
				E = "fx"
			}
			if (F === g) {
				return o.queue(this[0], E)
			}
			return this.each( function() {
				var G = o.queue(this, E, F);
				if (E == "fx" && G.length == 1) {
					G[0].call(this)
				}
			})
		},
		dequeue : function(E) {
			return this.each( function() {
				o.dequeue(this, E)
			})
		}
	});
	( function() {
		var R = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^[\]]*\]|['"][^'"]*['"]|[^[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?/g, L = 0, H = Object.prototype.toString;
		var F = function(Y, U, ab, ac) {
			ab = ab || [];
			U = U || document;
			if (U.nodeType !== 1 && U.nodeType !== 9) {
				return []
			}
			if (!Y || typeof Y !== "string") {
				return ab
			}
			var Z = [], W, af, ai, T, ad, V, X = true;
			R.lastIndex = 0;
			while ((W = R.exec(Y)) !== null) {
				Z.push(W[1]);
				if (W[2]) {
					V = RegExp.rightContext;
					break
				}
			}
			if (Z.length > 1 && M.exec(Y)) {
				if (Z.length === 2 && I.relative[Z[0]]) {
					af = J(Z[0] + Z[1], U)
				} else {
					af = I.relative[Z[0]] ? [ U ] : F(Z.shift(), U);
					while (Z.length) {
						Y = Z.shift();
						if (I.relative[Y]) {
							Y += Z.shift()
						}
						af = J(Y, af)
					}
				}
			} else {
				var ae = ac ? {
					expr :Z.pop(),
					set :E(ac)
				} : F
						.find(Z.pop(),
								Z.length === 1 && U.parentNode ? U.parentNode
										: U, Q(U));
				af = F.filter(ae.expr, ae.set);
				if (Z.length > 0) {
					ai = E(af)
				} else {
					X = false
				}
				while (Z.length) {
					var ah = Z.pop(), ag = ah;
					if (!I.relative[ah]) {
						ah = ""
					} else {
						ag = Z.pop()
					}
					if (ag == null) {
						ag = U
					}
					I.relative[ah](ai, ag, Q(U))
				}
			}
			if (!ai) {
				ai = af
			}
			if (!ai) {
				throw "Syntax error, unrecognized expression: " + (ah || Y)
			}
			if (H.call(ai) === "[object Array]") {
				if (!X) {
					ab.push.apply(ab, ai)
				} else {
					if (U.nodeType === 1) {
						for ( var aa = 0; ai[aa] != null; aa++) {
							if (ai[aa]
									&& (ai[aa] === true || ai[aa].nodeType === 1
											&& K(U, ai[aa]))) {
								ab.push(af[aa])
							}
						}
					} else {
						for ( var aa = 0; ai[aa] != null; aa++) {
							if (ai[aa] && ai[aa].nodeType === 1) {
								ab.push(af[aa])
							}
						}
					}
				}
			} else {
				E(ai, ab)
			}
			if (V) {
				F(V, U, ab, ac);
				if (G) {
					hasDuplicate = false;
					ab.sort(G);
					if (hasDuplicate) {
						for ( var aa = 1; aa < ab.length; aa++) {
							if (ab[aa] === ab[aa - 1]) {
								ab.splice(aa--, 1)
							}
						}
					}
				}
			}
			return ab
		};
		F.matches = function(T, U) {
			return F(T, null, null, U)
		};
		F.find = function(aa, T, ab) {
			var Z, X;
			if (!aa) {
				return []
			}
			for ( var W = 0, V = I.order.length; W < V; W++) {
				var Y = I.order[W], X;
				if ((X = I.match[Y].exec(aa))) {
					var U = RegExp.leftContext;
					if (U.substr(U.length - 1) !== "\\") {
						X[1] = (X[1] || "").replace(/\\/g, "");
						Z = I.find[Y](X, T, ab);
						if (Z != null) {
							aa = aa.replace(I.match[Y], "");
							break
						}
					}
				}
			}
			if (!Z) {
				Z = T.getElementsByTagName("*")
			}
			return {
				set :Z,
				expr :aa
			}
		};
		F.filter = function(ad, ac, ag, W) {
			var V = ad, ai = [], aa = ac, Y, T, Z = ac && ac[0] && Q(ac[0]);
			while (ad && ac.length) {
				for ( var ab in I.filter) {
					if ((Y = I.match[ab].exec(ad)) != null) {
						var U = I.filter[ab], ah, af;
						T = false;
						if (aa == ai) {
							ai = []
						}
						if (I.preFilter[ab]) {
							Y = I.preFilter[ab](Y, aa, ag, ai, W, Z);
							if (!Y) {
								T = ah = true
							} else {
								if (Y === true) {
									continue
								}
							}
						}
						if (Y) {
							for ( var X = 0; (af = aa[X]) != null; X++) {
								if (af) {
									ah = U(af, Y, X, aa);
									var ae = W ^ !!ah;
									if (ag && ah != null) {
										if (ae) {
											T = true
										} else {
											aa[X] = false
										}
									} else {
										if (ae) {
											ai.push(af);
											T = true
										}
									}
								}
							}
						}
						if (ah !== g) {
							if (!ag) {
								aa = ai
							}
							ad = ad.replace(I.match[ab], "");
							if (!T) {
								return []
							}
							break
						}
					}
				}
				if (ad == V) {
					if (T == null) {
						throw "Syntax error, unrecognized expression: " + ad
					} else {
						break
					}
				}
				V = ad
			}
			return aa
		};
		var I = F.selectors = {
			order : [ "ID", "NAME", "TAG" ],
			match : {
				ID :/#((?:[\w\u00c0-\uFFFF_-]|\\.)+)/,
				CLASS :/\.((?:[\w\u00c0-\uFFFF_-]|\\.)+)/,
				NAME :/\[name=['"]*((?:[\w\u00c0-\uFFFF_-]|\\.)+)['"]*\]/,
				ATTR :/\[\s*((?:[\w\u00c0-\uFFFF_-]|\\.)+)\s*(?:(\S?=)\s*(['"]*)(.*?)\3|)\s*\]/,
				TAG :/^((?:[\w\u00c0-\uFFFF\*_-]|\\.)+)/,
				CHILD :/:(only|nth|last|first)-child(?:\((even|odd|[\dn+-]*)\))?/,
				POS :/:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^-]|$)/,
				PSEUDO :/:((?:[\w\u00c0-\uFFFF_-]|\\.)+)(?:\((['"]*)((?:\([^\)]+\)|[^\2\(\)]*)+)\2\))?/
			},
			attrMap : {
				"class" :"className",
				"for" :"htmlFor"
			},
			attrHandle : {
				href : function(T) {
					return T.getAttribute("href")
				}
			},
			relative : {
				"+" : function(aa, T, Z) {
					var X = typeof T === "string", ab = X && !/\W/.test(T), Y = X
							&& !ab;
					if (ab && !Z) {
						T = T.toUpperCase()
					}
					for ( var W = 0, V = aa.length, U; W < V; W++) {
						if ((U = aa[W])) {
							while ((U = U.previousSibling) && U.nodeType !== 1) {
							}
							aa[W] = Y || U && U.nodeName === T ? U || false
									: U === T
						}
					}
					if (Y) {
						F.filter(T, aa, true)
					}
				},
				">" : function(Z, U, aa) {
					var X = typeof U === "string";
					if (X && !/\W/.test(U)) {
						U = aa ? U : U.toUpperCase();
						for ( var V = 0, T = Z.length; V < T; V++) {
							var Y = Z[V];
							if (Y) {
								var W = Y.parentNode;
								Z[V] = W.nodeName === U ? W : false
							}
						}
					} else {
						for ( var V = 0, T = Z.length; V < T; V++) {
							var Y = Z[V];
							if (Y) {
								Z[V] = X ? Y.parentNode : Y.parentNode === U
							}
						}
						if (X) {
							F.filter(U, Z, true)
						}
					}
				},
				"" : function(W, U, Y) {
					var V = L++, T = S;
					if (!U.match(/\W/)) {
						var X = U = Y ? U : U.toUpperCase();
						T = P
					}
					T("parentNode", U, V, W, X, Y)
				},
				"~" : function(W, U, Y) {
					var V = L++, T = S;
					if (typeof U === "string" && !U.match(/\W/)) {
						var X = U = Y ? U : U.toUpperCase();
						T = P
					}
					T("previousSibling", U, V, W, X, Y)
				}
			},
			find : {
				ID : function(U, V, W) {
					if (typeof V.getElementById !== "undefined" && !W) {
						var T = V.getElementById(U[1]);
						return T ? [ T ] : []
					}
				},
				NAME : function(V, Y, Z) {
					if (typeof Y.getElementsByName !== "undefined") {
						var U = [], X = Y.getElementsByName(V[1]);
						for ( var W = 0, T = X.length; W < T; W++) {
							if (X[W].getAttribute("name") === V[1]) {
								U.push(X[W])
							}
						}
						return U.length === 0 ? null : U
					}
				},
				TAG : function(T, U) {
					return U.getElementsByTagName(T[1])
				}
			},
			preFilter : {
				CLASS : function(W, U, V, T, Z, aa) {
					W = " " + W[1].replace(/\\/g, "") + " ";
					if (aa) {
						return W
					}
					for ( var X = 0, Y; (Y = U[X]) != null; X++) {
						if (Y) {
							if (Z
									^ (Y.className && (" " + Y.className + " ")
											.indexOf(W) >= 0)) {
								if (!V) {
									T.push(Y)
								}
							} else {
								if (V) {
									U[X] = false
								}
							}
						}
					}
					return false
				},
				ID : function(T) {
					return T[1].replace(/\\/g, "")
				},
				TAG : function(U, T) {
					for ( var V = 0; T[V] === false; V++) {
					}
					return T[V] && Q(T[V]) ? U[1] : U[1].toUpperCase()
				},
				CHILD : function(T) {
					if (T[1] == "nth") {
						var U = /(-?)(\d*)n((?:\+|-)?\d*)/.exec(T[2] == "even"
								&& "2n" || T[2] == "odd" && "2n+1"
								|| !/\D/.test(T[2]) && "0n+" + T[2] || T[2]);
						T[2] = (U[1] + (U[2] || 1)) - 0;
						T[3] = U[3] - 0
					}
					T[0] = L++;
					return T
				},
				ATTR : function(X, U, V, T, Y, Z) {
					var W = X[1].replace(/\\/g, "");
					if (!Z && I.attrMap[W]) {
						X[1] = I.attrMap[W]
					}
					if (X[2] === "~=") {
						X[4] = " " + X[4] + " "
					}
					return X
				},
				PSEUDO : function(X, U, V, T, Y) {
					if (X[1] === "not") {
						if (X[3].match(R).length > 1 || /^\w/.test(X[3])) {
							X[3] = F(X[3], null, null, U)
						} else {
							var W = F.filter(X[3], U, V, true ^ Y);
							if (!V) {
								T.push.apply(T, W)
							}
							return false
						}
					} else {
						if (I.match.POS.test(X[0]) || I.match.CHILD.test(X[0])) {
							return true
						}
					}
					return X
				},
				POS : function(T) {
					T.unshift(true);
					return T
				}
			},
			filters : {
				enabled : function(T) {
					return T.disabled === false && T.type !== "hidden"
				},
				disabled : function(T) {
					return T.disabled === true
				},
				checked : function(T) {
					return T.checked === true
				},
				selected : function(T) {
					T.parentNode.selectedIndex;
					return T.selected === true
				},
				parent : function(T) {
					return !!T.firstChild
				},
				empty : function(T) {
					return !T.firstChild
				},
				has : function(V, U, T) {
					return !!F(T[3], V).length
				},
				header : function(T) {
					return /h\d/i.test(T.nodeName)
				},
				text : function(T) {
					return "text" === T.type
				},
				radio : function(T) {
					return "radio" === T.type
				},
				checkbox : function(T) {
					return "checkbox" === T.type
				},
				file : function(T) {
					return "file" === T.type
				},
				password : function(T) {
					return "password" === T.type
				},
				submit : function(T) {
					return "submit" === T.type
				},
				image : function(T) {
					return "image" === T.type
				},
				reset : function(T) {
					return "reset" === T.type
				},
				button : function(T) {
					return "button" === T.type
							|| T.nodeName.toUpperCase() === "BUTTON"
				},
				input : function(T) {
					return /input|select|textarea|button/i.test(T.nodeName)
				}
			},
			setFilters : {
				first : function(U, T) {
					return T === 0
				},
				last : function(V, U, T, W) {
					return U === W.length - 1
				},
				even : function(U, T) {
					return T % 2 === 0
				},
				odd : function(U, T) {
					return T % 2 === 1
				},
				lt : function(V, U, T) {
					return U < T[3] - 0
				},
				gt : function(V, U, T) {
					return U > T[3] - 0
				},
				nth : function(V, U, T) {
					return T[3] - 0 == U
				},
				eq : function(V, U, T) {
					return T[3] - 0 == U
				}
			},
			filter : {
				PSEUDO : function(Z, V, W, aa) {
					var U = V[1], X = I.filters[U];
					if (X) {
						return X(Z, W, V, aa)
					} else {
						if (U === "contains") {
							return (Z.textContent || Z.innerText || "")
									.indexOf(V[3]) >= 0
						} else {
							if (U === "not") {
								var Y = V[3];
								for ( var W = 0, T = Y.length; W < T; W++) {
									if (Y[W] === Z) {
										return false
									}
								}
								return true
							}
						}
					}
				},
				CHILD : function(T, W) {
					var Z = W[1], U = T;
					switch (Z) {
					case "only":
					case "first":
						while (U = U.previousSibling) {
							if (U.nodeType === 1) {
								return false
							}
						}
						if (Z == "first") {
							return true
						}
						U = T;
					case "last":
						while (U = U.nextSibling) {
							if (U.nodeType === 1) {
								return false
							}
						}
						return true;
					case "nth":
						var V = W[2], ac = W[3];
						if (V == 1 && ac == 0) {
							return true
						}
						var Y = W[0], ab = T.parentNode;
						if (ab && (ab.sizcache !== Y || !T.nodeIndex)) {
							var X = 0;
							for (U = ab.firstChild; U; U = U.nextSibling) {
								if (U.nodeType === 1) {
									U.nodeIndex = ++X
								}
							}
							ab.sizcache = Y
						}
						var aa = T.nodeIndex - ac;
						if (V == 0) {
							return aa == 0
						} else {
							return (aa % V == 0 && aa / V >= 0)
						}
					}
				},
				ID : function(U, T) {
					return U.nodeType === 1 && U.getAttribute("id") === T
				},
				TAG : function(U, T) {
					return (T === "*" && U.nodeType === 1) || U.nodeName === T
				},
				CLASS : function(U, T) {
					return (" " + (U.className || U.getAttribute("class")) + " ")
							.indexOf(T) > -1
				},
				ATTR : function(Y, W) {
					var V = W[1], T = I.attrHandle[V] ? I.attrHandle[V](Y)
							: Y[V] != null ? Y[V] : Y.getAttribute(V), Z = T
							+ "", X = W[2], U = W[4];
					return T == null ? X === "!="
							: X === "=" ? Z === U
									: X === "*=" ? Z.indexOf(U) >= 0
											: X === "~=" ? (" " + Z + " ")
													.indexOf(U) >= 0
													: !U ? Z && T !== false
															: X === "!=" ? Z != U
																	: X === "^=" ? Z
																			.indexOf(U) === 0
																			: X === "$=" ? Z
																					.substr(Z.length
																							- U.length) === U
																					: X === "|=" ? Z === U
																							|| Z
																									.substr(
																											0,
																											U.length + 1) === U
																									+ "-"
																							: false
				},
				POS : function(X, U, V, Y) {
					var T = U[2], W = I.setFilters[T];
					if (W) {
						return W(X, V, U, Y)
					}
				}
			}
		};
		var M = I.match.POS;
		for ( var O in I.match) {
			I.match[O] = RegExp(I.match[O].source
					+ /(?![^\[]*\])(?![^\(]*\))/.source)
		}
		var E = function(U, T) {
			U = Array.prototype.slice.call(U);
			if (T) {
				T.push.apply(T, U);
				return T
			}
			return U
		};
		try {
			Array.prototype.slice.call(document.documentElement.childNodes)
		} catch (N) {
			E = function(X, W) {
				var U = W || [];
				if (H.call(X) === "[object Array]") {
					Array.prototype.push.apply(U, X)
				} else {
					if (typeof X.length === "number") {
						for ( var V = 0, T = X.length; V < T; V++) {
							U.push(X[V])
						}
					} else {
						for ( var V = 0; X[V]; V++) {
							U.push(X[V])
						}
					}
				}
				return U
			}
		}
		var G;
		if (document.documentElement.compareDocumentPosition) {
			G = function(U, T) {
				var V = U.compareDocumentPosition(T) & 4 ? -1 : U === T ? 0 : 1;
				if (V === 0) {
					hasDuplicate = true
				}
				return V
			}
		} else {
			if ("sourceIndex" in document.documentElement) {
				G = function(U, T) {
					var V = U.sourceIndex - T.sourceIndex;
					if (V === 0) {
						hasDuplicate = true
					}
					return V
				}
			} else {
				if (document.createRange) {
					G = function(W, U) {
						var V = W.ownerDocument.createRange(), T = U.ownerDocument
								.createRange();
						V.selectNode(W);
						V.collapse(true);
						T.selectNode(U);
						T.collapse(true);
						var X = V.compareBoundaryPoints(Range.START_TO_END, T);
						if (X === 0) {
							hasDuplicate = true
						}
						return X
					}
				}
			}
		}
		( function() {
			var U = document.createElement("form"), V = "script"
					+ (new Date).getTime();
			U.innerHTML = "<input name='" + V + "'/>";
			var T = document.documentElement;
			T.insertBefore(U, T.firstChild);
			if (!!document.getElementById(V)) {
				I.find.ID = function(X, Y, Z) {
					if (typeof Y.getElementById !== "undefined" && !Z) {
						var W = Y.getElementById(X[1]);
						return W ? W.id === X[1]
								|| typeof W.getAttributeNode !== "undefined"
								&& W.getAttributeNode("id").nodeValue === X[1] ? [ W ]
								: g
								: []
					}
				};
				I.filter.ID = function(Y, W) {
					var X = typeof Y.getAttributeNode !== "undefined"
							&& Y.getAttributeNode("id");
					return Y.nodeType === 1 && X && X.nodeValue === W
				}
			}
			T.removeChild(U)
		})();
		( function() {
			var T = document.createElement("div");
			T.appendChild(document.createComment(""));
			if (T.getElementsByTagName("*").length > 0) {
				I.find.TAG = function(U, Y) {
					var X = Y.getElementsByTagName(U[1]);
					if (U[1] === "*") {
						var W = [];
						for ( var V = 0; X[V]; V++) {
							if (X[V].nodeType === 1) {
								W.push(X[V])
							}
						}
						X = W
					}
					return X
				}
			}
			T.innerHTML = "<a href='#'></a>";
			if (T.firstChild
					&& typeof T.firstChild.getAttribute !== "undefined"
					&& T.firstChild.getAttribute("href") !== "#") {
				I.attrHandle.href = function(U) {
					return U.getAttribute("href", 2)
				}
			}
		})();
		if (document.querySelectorAll) {
			( function() {
				var T = F, U = document.createElement("div");
				U.innerHTML = "<p class='TEST'></p>";
				if (U.querySelectorAll
						&& U.querySelectorAll(".TEST").length === 0) {
					return
				}
				F = function(Y, X, V, W) {
					X = X || document;
					if (!W && X.nodeType === 9 && !Q(X)) {
						try {
							return E(X.querySelectorAll(Y), V)
						} catch (Z) {
						}
					}
					return T(Y, X, V, W)
				};
				F.find = T.find;
				F.filter = T.filter;
				F.selectors = T.selectors;
				F.matches = T.matches
			})()
		}
		if (document.getElementsByClassName
				&& document.documentElement.getElementsByClassName) {
			( function() {
				var T = document.createElement("div");
				T.innerHTML = "<div class='test e'></div><div class='test'></div>";
				if (T.getElementsByClassName("e").length === 0) {
					return
				}
				T.lastChild.className = "e";
				if (T.getElementsByClassName("e").length === 1) {
					return
				}
				I.order.splice(1, 0, "CLASS");
				I.find.CLASS = function(U, V, W) {
					if (typeof V.getElementsByClassName !== "undefined" && !W) {
						return V.getElementsByClassName(U[1])
					}
				}
			})()
		}
		function P(U, Z, Y, ad, aa, ac) {
			var ab = U == "previousSibling" && !ac;
			for ( var W = 0, V = ad.length; W < V; W++) {
				var T = ad[W];
				if (T) {
					if (ab && T.nodeType === 1) {
						T.sizcache = Y;
						T.sizset = W
					}
					T = T[U];
					var X = false;
					while (T) {
						if (T.sizcache === Y) {
							X = ad[T.sizset];
							break
						}
						if (T.nodeType === 1 && !ac) {
							T.sizcache = Y;
							T.sizset = W
						}
						if (T.nodeName === Z) {
							X = T;
							break
						}
						T = T[U]
					}
					ad[W] = X
				}
			}
		}
		function S(U, Z, Y, ad, aa, ac) {
			var ab = U == "previousSibling" && !ac;
			for ( var W = 0, V = ad.length; W < V; W++) {
				var T = ad[W];
				if (T) {
					if (ab && T.nodeType === 1) {
						T.sizcache = Y;
						T.sizset = W
					}
					T = T[U];
					var X = false;
					while (T) {
						if (T.sizcache === Y) {
							X = ad[T.sizset];
							break
						}
						if (T.nodeType === 1) {
							if (!ac) {
								T.sizcache = Y;
								T.sizset = W
							}
							if (typeof Z !== "string") {
								if (T === Z) {
									X = true;
									break
								}
							} else {
								if (F.filter(Z, [ T ]).length > 0) {
									X = T;
									break
								}
							}
						}
						T = T[U]
					}
					ad[W] = X
				}
			}
		}
		var K = document.compareDocumentPosition ? function(U, T) {
			return U.compareDocumentPosition(T) & 16
		} : function(U, T) {
			return U !== T && (U.contains ? U.contains(T) : true)
		};
		var Q = function(T) {
			return T.nodeType === 9 && T.documentElement.nodeName !== "HTML"
					|| !!T.ownerDocument && Q(T.ownerDocument)
		};
		var J = function(T, aa) {
			var W = [], X = "", Y, V = aa.nodeType ? [ aa ] : aa;
			while ((Y = I.match.PSEUDO.exec(T))) {
				X += Y[0];
				T = T.replace(I.match.PSEUDO, "")
			}
			T = I.relative[T] ? T + "*" : T;
			for ( var Z = 0, U = V.length; Z < U; Z++) {
				F(T, V[Z], W)
			}
			return F.filter(X, W)
		};
		o.find = F;
		o.filter = F.filter;
		o.expr = F.selectors;
		o.expr[":"] = o.expr.filters;
		F.selectors.filters.hidden = function(T) {
			return T.offsetWidth === 0 || T.offsetHeight === 0
		};
		F.selectors.filters.visible = function(T) {
			return T.offsetWidth > 0 || T.offsetHeight > 0
		};
		F.selectors.filters.animated = function(T) {
			return o.grep(o.timers, function(U) {
				return T === U.elem
			}).length
		};
		o.multiFilter = function(V, T, U) {
			if (U) {
				V = ":not(" + V + ")"
			}
			return F.matches(V, T)
		};
		o.dir = function(V, U) {
			var T = [], W = V[U];
			while (W && W != document) {
				if (W.nodeType == 1) {
					T.push(W)
				}
				W = W[U]
			}
			return T
		};
		o.nth = function(X, T, V, W) {
			T = T || 1;
			var U = 0;
			for (; X; X = X[V]) {
				if (X.nodeType == 1 && ++U == T) {
					break
				}
			}
			return X
		};
		o.sibling = function(V, U) {
			var T = [];
			for (; V; V = V.nextSibling) {
				if (V.nodeType == 1 && V != U) {
					T.push(V)
				}
			}
			return T
		};
		return;
		l.Sizzle = F
	})();
	o.event = {
		add : function(I, F, H, K) {
			if (I.nodeType == 3 || I.nodeType == 8) {
				return
			}
			if (I.setInterval && I != l) {
				I = l
			}
			if (!H.guid) {
				H.guid = this.guid++
			}
			if (K !== g) {
				var G = H;
				H = this.proxy(G);
				H.data = K
			}
			var E = o.data(I, "events") || o.data(I, "events", {}), J = o.data(
					I, "handle")
					|| o
							.data(
									I,
									"handle",
									function() {
										return typeof o !== "undefined"
												&& !o.event.triggered ? o.event.handle
												.apply(arguments.callee.elem,
														arguments)
												: g
									});
			J.elem = I;
			o
					.each(F.split(/\s+/),
							function(M, N) {
								var O = N.split(".");
								N = O.shift();
								H.type = O.slice().sort().join(".");
								var L = E[N];
								if (o.event.specialAll[N]) {
									o.event.specialAll[N].setup.call(I, K, O)
								}
								if (!L) {
									L = E[N] = {};
									if (!o.event.special[N]
											|| o.event.special[N].setup.call(I,
													K, O) === false) {
										if (I.addEventListener) {
											I.addEventListener(N, J, false)
										} else {
											if (I.attachEvent) {
												I.attachEvent("on" + N, J)
											}
										}
									}
								}
								L[H.guid] = H;
								o.event.global[N] = true
							});
			I = null
		},
		guid :1,
		global : {},
		remove : function(K, H, J) {
			if (K.nodeType == 3 || K.nodeType == 8) {
				return
			}
			var G = o.data(K, "events"), F, E;
			if (G) {
				if (H === g || (typeof H === "string" && H.charAt(0) == ".")) {
					for ( var I in G) {
						this.remove(K, I + (H || ""))
					}
				} else {
					if (H.type) {
						J = H.handler;
						H = H.type
					}
					o.each(H.split(/\s+/), function(M, O) {
						var Q = O.split(".");
						O = Q.shift();
						var N = RegExp("(^|\\.)"
								+ Q.slice().sort().join(".*\\.") + "(\\.|$)");
						if (G[O]) {
							if (J) {
								delete G[O][J.guid]
							} else {
								for ( var P in G[O]) {
									if (N.test(G[O][P].type)) {
										delete G[O][P]
									}
								}
							}
							if (o.event.specialAll[O]) {
								o.event.specialAll[O].teardown.call(K, Q)
							}
							for (F in G[O]) {
								break
							}
							if (!F) {
								if (!o.event.special[O]
										|| o.event.special[O].teardown.call(K,
												Q) === false) {
									if (K.removeEventListener) {
										K.removeEventListener(O, o.data(K,
												"handle"), false)
									} else {
										if (K.detachEvent) {
											K.detachEvent("on" + O, o.data(K,
													"handle"))
										}
									}
								}
								F = null;
								delete G[O]
							}
						}
					})
				}
				for (F in G) {
					break
				}
				if (!F) {
					var L = o.data(K, "handle");
					if (L) {
						L.elem = null
					}
					o.removeData(K, "events");
					o.removeData(K, "handle")
				}
			}
		},
		trigger : function(I, K, H, E) {
			var G = I.type || I;
			if (!E) {
				I = typeof I === "object" ? I[h] ? I : o.extend(o.Event(G), I)
						: o.Event(G);
				if (G.indexOf("!") >= 0) {
					I.type = G = G.slice(0, -1);
					I.exclusive = true
				}
				if (!H) {
					I.stopPropagation();
					if (this.global[G]) {
						o.each(o.cache, function() {
							if (this.events && this.events[G]) {
								o.event.trigger(I, K, this.handle.elem)
							}
						})
					}
				}
				if (!H || H.nodeType == 3 || H.nodeType == 8) {
					return g
				}
				I.result = g;
				I.target = H;
				K = o.makeArray(K);
				K.unshift(I)
			}
			I.currentTarget = H;
			var J = o.data(H, "handle");
			if (J) {
				J.apply(H, K)
			}
			if ((!H[G] || (o.nodeName(H, "a") && G == "click")) && H["on" + G]
					&& H["on" + G].apply(H, K) === false) {
				I.result = false
			}
			if (!E && H[G] && !I.isDefaultPrevented()
					&& !(o.nodeName(H, "a") && G == "click")) {
				this.triggered = true;
				try {
					H[G]()
				} catch (L) {
				}
			}
			this.triggered = false;
			if (!I.isPropagationStopped()) {
				var F = H.parentNode || H.ownerDocument;
				if (F) {
					o.event.trigger(I, K, F, true)
				}
			}
		},
		handle : function(K) {
			var J, E;
			K = arguments[0] = o.event.fix(K || l.event);
			K.currentTarget = this;
			var L = K.type.split(".");
			K.type = L.shift();
			J = !L.length && !K.exclusive;
			var I = RegExp("(^|\\.)" + L.slice().sort().join(".*\\.")
					+ "(\\.|$)");
			E = (o.data(this, "events") || {})[K.type];
			for ( var G in E) {
				var H = E[G];
				if (J || I.test(H.type)) {
					K.handler = H;
					K.data = H.data;
					var F = H.apply(this, arguments);
					if (F !== g) {
						K.result = F;
						if (F === false) {
							K.preventDefault();
							K.stopPropagation()
						}
					}
					if (K.isImmediatePropagationStopped()) {
						break
					}
				}
			}
		},
		props :"altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode metaKey newValue originalTarget pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which"
				.split(" "),
		fix : function(H) {
			if (H[h]) {
				return H
			}
			var F = H;
			H = o.Event(F);
			for ( var G = this.props.length, J; G;) {
				J = this.props[--G];
				H[J] = F[J]
			}
			if (!H.target) {
				H.target = H.srcElement || document
			}
			if (H.target.nodeType == 3) {
				H.target = H.target.parentNode
			}
			if (!H.relatedTarget && H.fromElement) {
				H.relatedTarget = H.fromElement == H.target ? H.toElement
						: H.fromElement
			}
			if (H.pageX == null && H.clientX != null) {
				var I = document.documentElement, E = document.body;
				H.pageX = H.clientX
						+ (I && I.scrollLeft || E && E.scrollLeft || 0)
						- (I.clientLeft || 0);
				H.pageY = H.clientY
						+ (I && I.scrollTop || E && E.scrollTop || 0)
						- (I.clientTop || 0)
			}
			if (!H.which
					&& ((H.charCode || H.charCode === 0) ? H.charCode
							: H.keyCode)) {
				H.which = H.charCode || H.keyCode
			}
			if (!H.metaKey && H.ctrlKey) {
				H.metaKey = H.ctrlKey
			}
			if (!H.which && H.button) {
				H.which = (H.button & 1 ? 1 : (H.button & 2 ? 3
						: (H.button & 4 ? 2 : 0)))
			}
			return H
		},
		proxy : function(F, E) {
			E = E || function() {
				return F.apply(this, arguments)
			};
			E.guid = F.guid = F.guid || E.guid || this.guid++;
			return E
		},
		special : {
			ready : {
				setup :B,
				teardown : function() {
				}
			}
		},
		specialAll : {
			live : {
				setup : function(E, F) {
					o.event.add(this, F[0], c)
				},
				teardown : function(G) {
					if (G.length) {
						var E = 0, F = RegExp("(^|\\.)" + G[0] + "(\\.|$)");
						o.each((o.data(this, "events").live || {}), function() {
							if (F.test(this.type)) {
								E++
							}
						});
						if (E < 1) {
							o.event.remove(this, G[0], c)
						}
					}
				}
			}
		}
	};
	o.Event = function(E) {
		if (!this.preventDefault) {
			return new o.Event(E)
		}
		if (E && E.type) {
			this.originalEvent = E;
			this.type = E.type
		} else {
			this.type = E
		}
		this.timeStamp = e();
		this[h] = true
	};
	function k() {
		return false
	}
	function u() {
		return true
	}
	o.Event.prototype = {
		preventDefault : function() {
			this.isDefaultPrevented = u;
			var E = this.originalEvent;
			if (!E) {
				return
			}
			if (E.preventDefault) {
				E.preventDefault()
			}
			E.returnValue = false
		},
		stopPropagation : function() {
			this.isPropagationStopped = u;
			var E = this.originalEvent;
			if (!E) {
				return
			}
			if (E.stopPropagation) {
				E.stopPropagation()
			}
			E.cancelBubble = true
		},
		stopImmediatePropagation : function() {
			this.isImmediatePropagationStopped = u;
			this.stopPropagation()
		},
		isDefaultPrevented :k,
		isPropagationStopped :k,
		isImmediatePropagationStopped :k
	};
	var a = function(F) {
		var E = F.relatedTarget;
		while (E && E != this) {
			try {
				E = E.parentNode
			} catch (G) {
				E = this
			}
		}
		if (E != this) {
			F.type = F.data;
			o.event.handle.apply(this, arguments)
		}
	};
	o.each( {
		mouseover :"mouseenter",
		mouseout :"mouseleave"
	}, function(F, E) {
		o.event.special[E] = {
			setup : function() {
				o.event.add(this, F, a, E)
			},
			teardown : function() {
				o.event.remove(this, F, a)
			}
		}
	});
	o.fn.extend( {
		bind : function(F, G, E) {
			return F == "unload" ? this.one(F, G, E) : this.each( function() {
				o.event.add(this, F, E || G, E && G)
			})
		},
		one : function(G, H, F) {
			var E = o.event.proxy(F || H, function(I) {
				o(this).unbind(I, E);
				return (F || H).apply(this, arguments)
			});
			return this.each( function() {
				o.event.add(this, G, E, F && H)
			})
		},
		unbind : function(F, E) {
			return this.each( function() {
				o.event.remove(this, F, E)
			})
		},
		trigger : function(E, F) {
			return this.each( function() {
				o.event.trigger(E, F, this)
			})
		},
		triggerHandler : function(E, G) {
			if (this[0]) {
				var F = o.Event(E);
				F.preventDefault();
				F.stopPropagation();
				o.event.trigger(F, G, this[0]);
				return F.result
			}
		},
		toggle : function(G) {
			var E = arguments, F = 1;
			while (F < E.length) {
				o.event.proxy(G, E[F++])
			}
			return this.click(o.event.proxy(G, function(H) {
				this.lastToggle = (this.lastToggle || 0) % F;
				H.preventDefault();
				return E[this.lastToggle++].apply(this, arguments) || false
			}))
		},
		hover : function(E, F) {
			return this.mouseenter(E).mouseleave(F)
		},
		ready : function(E) {
			B();
			if (o.isReady) {
				E.call(document, o)
			} else {
				o.readyList.push(E)
			}
			return this
		},
		live : function(G, F) {
			var E = o.event.proxy(F);
			E.guid += this.selector + G;
			o(document).bind(i(G, this.selector), this.selector, E);
			return this
		},
		die : function(F, E) {
			o(document).unbind(i(F, this.selector), E ? {
				guid :E.guid + this.selector + F
			} : null);
			return this
		}
	});
	function c(H) {
		var E = RegExp("(^|\\.)" + H.type + "(\\.|$)"), G = true, F = [];
		o.each(o.data(this, "events").live || [], function(I, J) {
			if (E.test(J.type)) {
				var K = o(H.target).closest(J.data)[0];
				if (K) {
					F.push( {
						elem :K,
						fn :J
					})
				}
			}
		});
		F.sort( function(J, I) {
			return o.data(J.elem, "closest") - o.data(I.elem, "closest")
		});
		o.each(F, function() {
			if (this.fn.call(this.elem, H, this.fn.data) === false) {
				return (G = false)
			}
		});
		return G
	}
	function i(F, E) {
		return [ "live", F, E.replace(/\./g, "`").replace(/ /g, "|") ]
				.join(".")
	}
	o.extend( {
		isReady :false,
		readyList : [],
		ready : function() {
			if (!o.isReady) {
				o.isReady = true;
				if (o.readyList) {
					o.each(o.readyList, function() {
						this.call(document, o)
					});
					o.readyList = null
				}
				o(document).triggerHandler("ready")
			}
		}
	});
	var x = false;
	function B() {
		if (x) {
			return
		}
		x = true;
		if (document.addEventListener) {
			document.addEventListener("DOMContentLoaded", function() {
				document.removeEventListener("DOMContentLoaded",
						arguments.callee, false);
				o.ready()
			}, false)
		} else {
			if (document.attachEvent) {
				document.attachEvent("onreadystatechange", function() {
					if (document.readyState === "complete") {
						document.detachEvent("onreadystatechange",
								arguments.callee);
						o.ready()
					}
				});
				if (document.documentElement.doScroll && l == l.top) {
					( function() {
						if (o.isReady) {
							return
						}
						try {
							document.documentElement.doScroll("left")
						} catch (E) {
							setTimeout(arguments.callee, 0);
							return
						}
						o.ready()
					})()
				}
			}
		}
		o.event.add(l, "load", o.ready)
	}
	o
			.each(
					("blur,focus,load,resize,scroll,unload,click,dblclick,mousedown,mouseup,mousemove,mouseover,mouseout,mouseenter,mouseleave,change,select,submit,keydown,keypress,keyup,error")
							.split(","), function(F, E) {
						o.fn[E] = function(G) {
							return G ? this.bind(E, G) : this.trigger(E)
						}
					});
	o(l).bind("unload", function() {
		for ( var E in o.cache) {
			if (E != 1 && o.cache[E].handle) {
				o.event.remove(o.cache[E].handle.elem)
			}
		}
	});
	( function() {
		o.support = {};
		var F = document.documentElement, G = document.createElement("script"), K = document
				.createElement("div"), J = "script" + (new Date).getTime();
		K.style.display = "none";
		K.innerHTML = '   <link/><table></table><a href="/a" style="color:red;float:left;opacity:.5;">a</a><select><option>text</option></select><object><param/></object>';
		var H = K.getElementsByTagName("*"), E = K.getElementsByTagName("a")[0];
		if (!H || !H.length || !E) {
			return
		}
		o.support = {
			leadingWhitespace :K.firstChild.nodeType == 3,
			tbody :!K.getElementsByTagName("tbody").length,
			objectAll :!!K.getElementsByTagName("object")[0]
					.getElementsByTagName("*").length,
			htmlSerialize :!!K.getElementsByTagName("link").length,
			style :/red/.test(E.getAttribute("style")),
			hrefNormalized :E.getAttribute("href") === "/a",
			opacity :E.style.opacity === "0.5",
			cssFloat :!!E.style.cssFloat,
			scriptEval :false,
			noCloneEvent :true,
			boxModel :null
		};
		G.type = "text/javascript";
		try {
			G.appendChild(document.createTextNode("window." + J + "=1;"))
		} catch (I) {
		}
		F.insertBefore(G, F.firstChild);
		if (l[J]) {
			o.support.scriptEval = true;
			delete l[J]
		}
		F.removeChild(G);
		if (K.attachEvent && K.fireEvent) {
			K.attachEvent("onclick", function() {
				o.support.noCloneEvent = false;
				K.detachEvent("onclick", arguments.callee)
			});
			K.cloneNode(true).fireEvent("onclick")
		}
		o( function() {
			var L = document.createElement("div");
			L.style.width = L.style.paddingLeft = "1px";
			document.body.appendChild(L);
			o.boxModel = o.support.boxModel = L.offsetWidth === 2;
			document.body.removeChild(L).style.display = "none"
		})
	})();
	var w = o.support.cssFloat ? "cssFloat" : "styleFloat";
	o.props = {
		"for" :"htmlFor",
		"class" :"className",
		"float" :w,
		cssFloat :w,
		styleFloat :w,
		readonly :"readOnly",
		maxlength :"maxLength",
		cellspacing :"cellSpacing",
		rowspan :"rowSpan",
		tabindex :"tabIndex"
	};
	o.fn
			.extend( {
				_load :o.fn.load,
				load : function(G, J, K) {
					if (typeof G !== "string") {
						return this._load(G)
					}
					var I = G.indexOf(" ");
					if (I >= 0) {
						var E = G.slice(I, G.length);
						G = G.slice(0, I)
					}
					var H = "GET";
					if (J) {
						if (o.isFunction(J)) {
							K = J;
							J = null
						} else {
							if (typeof J === "object") {
								J = o.param(J);
								H = "POST"
							}
						}
					}
					var F = this;
					o
							.ajax( {
								url :G,
								type :H,
								dataType :"html",
								data :J,
								complete : function(M, L) {
									if (L == "success" || L == "notmodified") {
										F
												.html(E ? o("<div/>")
														.append(
																M.responseText
																		.replace(
																				/<script(.|\s)*?\/script>/g,
																				""))
														.find(E)
														: M.responseText)
									}
									if (K) {
										F.each(K, [ M.responseText, L, M ])
									}
								}
							});
					return this
				},
				serialize : function() {
					return o.param(this.serializeArray())
				},
				serializeArray : function() {
					return this
							.map(
									function() {
										return this.elements ? o
												.makeArray(this.elements)
												: this
									})
							.filter(
									function() {
										return this.name
												&& !this.disabled
												&& (this.checked
														|| /select|textarea/i
																.test(this.nodeName) || /text|hidden|password|search/i
														.test(this.type))
									}).map(
									function(E, F) {
										var G = o(this).val();
										return G == null ? null
												: o.isArray(G) ? o.map(G,
														function(I, H) {
															return {
																name :F.name,
																value :I
															}
														}) : {
													name :F.name,
													value :G
												}
									}).get()
				}
			});
	o.each("ajaxStart,ajaxStop,ajaxComplete,ajaxError,ajaxSuccess,ajaxSend"
			.split(","), function(E, F) {
		o.fn[F] = function(G) {
			return this.bind(F, G)
		}
	});
	var r = e();
	o
			.extend( {
				get : function(E, G, H, F) {
					if (o.isFunction(G)) {
						H = G;
						G = null
					}
					return o.ajax( {
						type :"GET",
						url :E,
						data :G,
						success :H,
						dataType :F
					})
				},
				getScript : function(E, F) {
					return o.get(E, null, F, "script")
				},
				getJSON : function(E, F, G) {
					return o.get(E, F, G, "json")
				},
				post : function(E, G, H, F) {
					if (o.isFunction(G)) {
						H = G;
						G = {}
					}
					return o.ajax( {
						type :"POST",
						url :E,
						data :G,
						success :H,
						dataType :F
					})
				},
				ajaxSetup : function(E) {
					o.extend(o.ajaxSettings, E)
				},
				ajaxSettings : {
					url :location.href,
					global :true,
					type :"GET",
					contentType :"application/x-www-form-urlencoded",
					processData :true,
					async :true,
					xhr : function() {
						return l.ActiveXObject ? new ActiveXObject(
								"Microsoft.XMLHTTP") : new XMLHttpRequest()
					},
					accepts : {
						xml :"application/xml, text/xml",
						html :"text/html",
						script :"text/javascript, application/javascript",
						json :"application/json, text/javascript",
						text :"text/plain",
						_default :"*/*"
					}
				},
				lastModified : {},
				ajax : function(M) {
					M = o
							.extend(true, M, o.extend(true, {}, o.ajaxSettings,
									M));
					var W, F = /=\?(&|$)/g, R, V, G = M.type.toUpperCase();
					if (M.data && M.processData && typeof M.data !== "string") {
						M.data = o.param(M.data)
					}
					if (M.dataType == "jsonp") {
						if (G == "GET") {
							if (!M.url.match(F)) {
								M.url += (M.url.match(/\?/) ? "&" : "?")
										+ (M.jsonp || "callback") + "=?"
							}
						} else {
							if (!M.data || !M.data.match(F)) {
								M.data = (M.data ? M.data + "&" : "")
										+ (M.jsonp || "callback") + "=?"
							}
						}
						M.dataType = "json"
					}
					if (M.dataType == "json"
							&& (M.data && M.data.match(F) || M.url.match(F))) {
						W = "jsonp" + r++;
						if (M.data) {
							M.data = (M.data + "").replace(F, "=" + W + "$1")
						}
						M.url = M.url.replace(F, "=" + W + "$1");
						M.dataType = "script";
						l[W] = function(X) {
							V = X;
							I();
							L();
							l[W] = g;
							try {
								delete l[W]
							} catch (Y) {
							}
							if (H) {
								H.removeChild(T)
							}
						}
					}
					if (M.dataType == "script" && M.cache == null) {
						M.cache = false
					}
					if (M.cache === false && G == "GET") {
						var E = e();
						var U = M.url.replace(/(\?|&)_=.*?(&|$)/, "$1_=" + E
								+ "$2");
						M.url = U
								+ ((U == M.url) ? (M.url.match(/\?/) ? "&"
										: "?")
										+ "_=" + E : "")
					}
					if (M.data && G == "GET") {
						M.url += (M.url.match(/\?/) ? "&" : "?") + M.data;
						M.data = null
					}
					if (M.global && !o.active++) {
						o.event.trigger("ajaxStart")
					}
					var Q = /^(\w+:)?\/\/([^\/?#]+)/.exec(M.url);
					if (M.dataType == "script"
							&& G == "GET"
							&& Q
							&& (Q[1] && Q[1] != location.protocol || Q[2] != location.host)) {
						var H = document.getElementsByTagName("head")[0];
						var T = document.createElement("script");
						T.src = M.url;
						if (M.scriptCharset) {
							T.charset = M.scriptCharset
						}
						if (!W) {
							var O = false;
							T.onload = T.onreadystatechange = function() {
								if (!O
										&& (!this.readyState
												|| this.readyState == "loaded" || this.readyState == "complete")) {
									O = true;
									I();
									L();
									T.onload = T.onreadystatechange = null;
									H.removeChild(T)
								}
							}
						}
						H.appendChild(T);
						return g
					}
					var K = false;
					var J = M.xhr();
					if (M.username) {
						J.open(G, M.url, M.async, M.username, M.password)
					} else {
						J.open(G, M.url, M.async)
					}
					try {
						if (M.data) {
							J.setRequestHeader("Content-Type", M.contentType)
						}
						if (M.ifModified) {
							J.setRequestHeader("If-Modified-Since",
									o.lastModified[M.url]
											|| "Thu, 01 Jan 1970 00:00:00 GMT")
						}
						J
								.setRequestHeader("X-Requested-With",
										"XMLHttpRequest");
						J
								.setRequestHeader(
										"Accept",
										M.dataType && M.accepts[M.dataType] ? M.accepts[M.dataType]
												+ ", */*"
												: M.accepts._default)
					} catch (S) {
					}
					if (M.beforeSend && M.beforeSend(J, M) === false) {
						if (M.global && !--o.active) {
							o.event.trigger("ajaxStop")
						}
						J.abort();
						return false
					}
					if (M.global) {
						o.event.trigger("ajaxSend", [ J, M ])
					}
					var N = function(X) {
						if (J.readyState == 0) {
							if (P) {
								clearInterval(P);
								P = null;
								if (M.global && !--o.active) {
									o.event.trigger("ajaxStop")
								}
							}
						} else {
							if (!K && J
									&& (J.readyState == 4 || X == "timeout")) {
								K = true;
								if (P) {
									clearInterval(P);
									P = null
								}
								R = X == "timeout" ? "timeout"
										: !o.httpSuccess(J) ? "error"
												: M.ifModified
														&& o.httpNotModified(J,
																M.url) ? "notmodified"
														: "success";
								if (R == "success") {
									try {
										V = o.httpData(J, M.dataType, M)
									} catch (Z) {
										R = "parsererror"
									}
								}
								if (R == "success") {
									var Y;
									try {
										Y = J
												.getResponseHeader("Last-Modified")
									} catch (Z) {
									}
									if (M.ifModified && Y) {
										o.lastModified[M.url] = Y
									}
									if (!W) {
										I()
									}
								} else {
									o.handleError(M, J, R)
								}
								L();
								if (X) {
									J.abort()
								}
								if (M.async) {
									J = null
								}
							}
						}
					};
					if (M.async) {
						var P = setInterval(N, 13);
						if (M.timeout > 0) {
							setTimeout( function() {
								if (J && !K) {
									N("timeout")
								}
							}, M.timeout)
						}
					}
					try {
						J.send(M.data)
					} catch (S) {
						o.handleError(M, J, null, S)
					}
					if (!M.async) {
						N()
					}
					function I() {
						if (M.success) {
							M.success(V, R)
						}
						if (M.global) {
							o.event.trigger("ajaxSuccess", [ J, M ])
						}
					}
					function L() {
						if (M.complete) {
							M.complete(J, R)
						}
						if (M.global) {
							o.event.trigger("ajaxComplete", [ J, M ])
						}
						if (M.global && !--o.active) {
							o.event.trigger("ajaxStop")
						}
					}
					return J
				},
				handleError : function(F, H, E, G) {
					if (F.error) {
						F.error(H, E, G)
					}
					if (F.global) {
						o.event.trigger("ajaxError", [ H, F, G ])
					}
				},
				active :0,
				httpSuccess : function(F) {
					try {
						return !F.status && location.protocol == "file:"
								|| (F.status >= 200 && F.status < 300)
								|| F.status == 304 || F.status == 1223
					} catch (E) {
					}
					return false
				},
				httpNotModified : function(G, E) {
					try {
						var H = G.getResponseHeader("Last-Modified");
						return G.status == 304 || H == o.lastModified[E]
					} catch (F) {
					}
					return false
				},
				httpData : function(J, H, G) {
					var F = J.getResponseHeader("content-type"), E = H == "xml"
							|| !H && F && F.indexOf("xml") >= 0, I = E ? J.responseXML
							: J.responseText;
					if (E && I.documentElement.tagName == "parsererror") {
						throw "parsererror"
					}
					if (G && G.dataFilter) {
						I = G.dataFilter(I, H)
					}
					if (typeof I === "string") {
						if (H == "script") {
							o.globalEval(I)
						}
						if (H == "json") {
							I = l["eval"]("(" + I + ")")
						}
					}
					return I
				},
				param : function(E) {
					var G = [];
					function H(I, J) {
						G[G.length] = encodeURIComponent(I) + "="
								+ encodeURIComponent(J)
					}
					if (o.isArray(E) || E.jquery) {
						o.each(E, function() {
							H(this.name, this.value)
						})
					} else {
						for ( var F in E) {
							if (o.isArray(E[F])) {
								o.each(E[F], function() {
									H(F, this)
								})
							} else {
								H(F, o.isFunction(E[F]) ? E[F]() : E[F])
							}
						}
					}
					return G.join("&").replace(/%20/g, "+")
				}
			});
	var m = {}, n, d = [
			[ "height", "marginTop", "marginBottom", "paddingTop",
					"paddingBottom" ],
			[ "width", "marginLeft", "marginRight", "paddingLeft",
					"paddingRight" ], [ "opacity" ] ];
	function t(F, E) {
		var G = {};
		o.each(d.concat.apply( [], d.slice(0, E)), function() {
			G[this] = F
		});
		return G
	}
	o.fn.extend( {
		show : function(J, L) {
			if (J) {
				return this.animate(t("show", 3), J, L)
			} else {
				for ( var H = 0, F = this.length; H < F; H++) {
					var E = o.data(this[H], "olddisplay");
					this[H].style.display = E || "";
					if (o.css(this[H], "display") === "none") {
						var G = this[H].tagName, K;
						if (m[G]) {
							K = m[G]
						} else {
							var I = o("<" + G + " />").appendTo("body");
							K = I.css("display");
							if (K === "none") {
								K = "block"
							}
							I.remove();
							m[G] = K
						}
						o.data(this[H], "olddisplay", K)
					}
				}
				for ( var H = 0, F = this.length; H < F; H++) {
					this[H].style.display = o.data(this[H], "olddisplay") || ""
				}
				return this
			}
		},
		hide : function(H, I) {
			if (H) {
				return this.animate(t("hide", 3), H, I)
			} else {
				for ( var G = 0, F = this.length; G < F; G++) {
					var E = o.data(this[G], "olddisplay");
					if (!E && E !== "none") {
						o
								.data(this[G], "olddisplay", o.css(this[G],
										"display"))
					}
				}
				for ( var G = 0, F = this.length; G < F; G++) {
					this[G].style.display = "none"
				}
				return this
			}
		},
		_toggle :o.fn.toggle,
		toggle : function(G, F) {
			var E = typeof G === "boolean";
			return o.isFunction(G) && o.isFunction(F) ? this._toggle.apply(
					this, arguments) : G == null || E ? this.each( function() {
				var H = E ? G : o(this).is(":hidden");
				o(this)[H ? "show" : "hide"]()
			}) : this.animate(t("toggle", 3), G, F)
		},
		fadeTo : function(E, G, F) {
			return this.animate( {
				opacity :G
			}, E, F)
		},
		animate : function(I, F, H, G) {
			var E = o.speed(F, H, G);
			return this[E.queue === false ? "each" : "queue"]( function() {
				var K = o.extend( {}, E), M, L = this.nodeType == 1
						&& o(this).is(":hidden"), J = this;
				for (M in I) {
					if (I[M] == "hide" && L || I[M] == "show" && !L) {
						return K.complete.call(this)
					}
					if ((M == "height" || M == "width") && this.style) {
						K.display = o.css(this, "display");
						K.overflow = this.style.overflow
					}
				}
				if (K.overflow != null) {
					this.style.overflow = "hidden"
				}
				K.curAnim = o.extend( {}, I);
				o.each(I,
						function(O, S) {
							var R = new o.fx(J, K, O);
							if (/toggle|show|hide/.test(S)) {
								R[S == "toggle" ? L ? "show" : "hide" : S](I)
							} else {
								var Q = S.toString().match(
										/^([+-]=)?([\d+-.]+)(.*)$/), T = R
										.cur(true) || 0;
								if (Q) {
									var N = parseFloat(Q[2]), P = Q[3] || "px";
									if (P != "px") {
										J.style[O] = (N || 1) + P;
										T = ((N || 1) / R.cur(true)) * T;
										J.style[O] = T + P
									}
									if (Q[1]) {
										N = ((Q[1] == "-=" ? -1 : 1) * N) + T
									}
									R.custom(T, N, P)
								} else {
									R.custom(T, S, "")
								}
							}
						});
				return true
			})
		},
		stop : function(F, E) {
			var G = o.timers;
			if (F) {
				this.queue( [])
			}
			this.each( function() {
				for ( var H = G.length - 1; H >= 0; H--) {
					if (G[H].elem == this) {
						if (E) {
							G[H](true)
						}
						G.splice(H, 1)
					}
				}
			});
			if (!E) {
				this.dequeue()
			}
			return this
		}
	});
	o.each( {
		slideDown :t("show", 1),
		slideUp :t("hide", 1),
		slideToggle :t("toggle", 1),
		fadeIn : {
			opacity :"show"
		},
		fadeOut : {
			opacity :"hide"
		}
	}, function(E, F) {
		o.fn[E] = function(G, H) {
			return this.animate(F, G, H)
		}
	});
	o.extend( {
		speed : function(G, H, F) {
			var E = typeof G === "object" ? G : {
				complete :F || !F && H || o.isFunction(G) && G,
				duration :G,
				easing :F && H || H && !o.isFunction(H) && H
			};
			E.duration = o.fx.off ? 0
					: typeof E.duration === "number" ? E.duration
							: o.fx.speeds[E.duration] || o.fx.speeds._default;
			E.old = E.complete;
			E.complete = function() {
				if (E.queue !== false) {
					o(this).dequeue()
				}
				if (o.isFunction(E.old)) {
					E.old.call(this)
				}
			};
			return E
		},
		easing : {
			linear : function(G, H, E, F) {
				return E + F * G
			},
			swing : function(G, H, E, F) {
				return ((-Math.cos(G * Math.PI) / 2) + 0.5) * F + E
			}
		},
		timers : [],
		fx : function(F, E, G) {
			this.options = E;
			this.elem = F;
			this.prop = G;
			if (!E.orig) {
				E.orig = {}
			}
		}
	});
	o.fx.prototype = {
		update : function() {
			if (this.options.step) {
				this.options.step.call(this.elem, this.now, this)
			}
			(o.fx.step[this.prop] || o.fx.step._default)(this);
			if ((this.prop == "height" || this.prop == "width")
					&& this.elem.style) {
				this.elem.style.display = "block"
			}
		},
		cur : function(F) {
			if (this.elem[this.prop] != null
					&& (!this.elem.style || this.elem.style[this.prop] == null)) {
				return this.elem[this.prop]
			}
			var E = parseFloat(o.css(this.elem, this.prop, F));
			return E && E > -10000 ? E : parseFloat(o.curCSS(this.elem,
					this.prop)) || 0
		},
		custom : function(I, H, G) {
			this.startTime = e();
			this.start = I;
			this.end = H;
			this.unit = G || this.unit || "px";
			this.now = this.start;
			this.pos = this.state = 0;
			var E = this;
			function F(J) {
				return E.step(J)
			}
			F.elem = this.elem;
			if (F() && o.timers.push(F) && !n) {
				n = setInterval( function() {
					var K = o.timers;
					for ( var J = 0; J < K.length; J++) {
						if (!K[J]()) {
							K.splice(J--, 1)
						}
					}
					if (!K.length) {
						clearInterval(n);
						n = g
					}
				}, 13)
			}
		},
		show : function() {
			this.options.orig[this.prop] = o.attr(this.elem.style, this.prop);
			this.options.show = true;
			this.custom(this.prop == "width" || this.prop == "height" ? 1 : 0,
					this.cur());
			o(this.elem).show()
		},
		hide : function() {
			this.options.orig[this.prop] = o.attr(this.elem.style, this.prop);
			this.options.hide = true;
			this.custom(this.cur(), 0)
		},
		step : function(H) {
			var G = e();
			if (H || G >= this.options.duration + this.startTime) {
				this.now = this.end;
				this.pos = this.state = 1;
				this.update();
				this.options.curAnim[this.prop] = true;
				var E = true;
				for ( var F in this.options.curAnim) {
					if (this.options.curAnim[F] !== true) {
						E = false
					}
				}
				if (E) {
					if (this.options.display != null) {
						this.elem.style.overflow = this.options.overflow;
						this.elem.style.display = this.options.display;
						if (o.css(this.elem, "display") == "none") {
							this.elem.style.display = "block"
						}
					}
					if (this.options.hide) {
						o(this.elem).hide()
					}
					if (this.options.hide || this.options.show) {
						for ( var I in this.options.curAnim) {
							o.attr(this.elem.style, I, this.options.orig[I])
						}
					}
					this.options.complete.call(this.elem)
				}
				return false
			} else {
				var J = G - this.startTime;
				this.state = J / this.options.duration;
				this.pos = o.easing[this.options.easing
						|| (o.easing.swing ? "swing" : "linear")](this.state,
						J, 0, 1, this.options.duration);
				this.now = this.start + ((this.end - this.start) * this.pos);
				this.update()
			}
			return true
		}
	};
	o.extend(o.fx, {
		speeds : {
			slow :600,
			fast :200,
			_default :400
		},
		step : {
			opacity : function(E) {
				o.attr(E.elem.style, "opacity", E.now)
			},
			_default : function(E) {
				if (E.elem.style && E.elem.style[E.prop] != null) {
					E.elem.style[E.prop] = E.now + E.unit
				} else {
					E.elem[E.prop] = E.now
				}
			}
		}
	});
	if (document.documentElement.getBoundingClientRect) {
		o.fn.offset = function() {
			if (!this[0]) {
				return {
					top :0,
					left :0
				}
			}
			if (this[0] === this[0].ownerDocument.body) {
				return o.offset.bodyOffset(this[0])
			}
			var G = this[0].getBoundingClientRect(), J = this[0].ownerDocument, F = J.body, E = J.documentElement, L = E.clientTop
					|| F.clientTop || 0, K = E.clientLeft || F.clientLeft || 0, I = G.top
					+ (self.pageYOffset || o.boxModel && E.scrollTop || F.scrollTop)
					- L, H = G.left
					+ (self.pageXOffset || o.boxModel && E.scrollLeft || F.scrollLeft)
					- K;
			return {
				top :I,
				left :H
			}
		}
	} else {
		o.fn.offset = function() {
			if (!this[0]) {
				return {
					top :0,
					left :0
				}
			}
			if (this[0] === this[0].ownerDocument.body) {
				return o.offset.bodyOffset(this[0])
			}
			o.offset.initialized || o.offset.initialize();
			var J = this[0], G = J.offsetParent, F = J, O = J.ownerDocument, M, H = O.documentElement, K = O.body, L = O.defaultView, E = L
					.getComputedStyle(J, null), N = J.offsetTop, I = J.offsetLeft;
			while ((J = J.parentNode) && J !== K && J !== H) {
				M = L.getComputedStyle(J, null);
				N -= J.scrollTop, I -= J.scrollLeft;
				if (J === G) {
					N += J.offsetTop, I += J.offsetLeft;
					if (o.offset.doesNotAddBorder
							&& !(o.offset.doesAddBorderForTableAndCells && /^t(able|d|h)$/i
									.test(J.tagName))) {
						N += parseInt(M.borderTopWidth, 10) || 0,
								I += parseInt(M.borderLeftWidth, 10) || 0
					}
					F = G, G = J.offsetParent
				}
				if (o.offset.subtractsBorderForOverflowNotVisible
						&& M.overflow !== "visible") {
					N += parseInt(M.borderTopWidth, 10) || 0, I += parseInt(
							M.borderLeftWidth, 10) || 0
				}
				E = M
			}
			if (E.position === "relative" || E.position === "static") {
				N += K.offsetTop, I += K.offsetLeft
			}
			if (E.position === "fixed") {
				N += Math.max(H.scrollTop, K.scrollTop), I += Math.max(
						H.scrollLeft, K.scrollLeft)
			}
			return {
				top :N,
				left :I
			}
		}
	}
	o.offset = {
		initialize : function() {
			if (this.initialized) {
				return
			}
			var L = document.body, F = document.createElement("div"), H, G, N, I, M, E, J = L.style.marginTop, K = '<div style="position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;"><div></div></div><table style="position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;" cellpadding="0" cellspacing="0"><tr><td></td></tr></table>';
			M = {
				position :"absolute",
				top :0,
				left :0,
				margin :0,
				border :0,
				width :"1px",
				height :"1px",
				visibility :"hidden"
			};
			for (E in M) {
				F.style[E] = M[E]
			}
			F.innerHTML = K;
			L.insertBefore(F, L.firstChild);
			H = F.firstChild, G = H.firstChild,
					I = H.nextSibling.firstChild.firstChild;
			this.doesNotAddBorder = (G.offsetTop !== 5);
			this.doesAddBorderForTableAndCells = (I.offsetTop === 5);
			H.style.overflow = "hidden", H.style.position = "relative";
			this.subtractsBorderForOverflowNotVisible = (G.offsetTop === -5);
			L.style.marginTop = "1px";
			this.doesNotIncludeMarginInBodyOffset = (L.offsetTop === 0);
			L.style.marginTop = J;
			L.removeChild(F);
			this.initialized = true
		},
		bodyOffset : function(E) {
			o.offset.initialized || o.offset.initialize();
			var G = E.offsetTop, F = E.offsetLeft;
			if (o.offset.doesNotIncludeMarginInBodyOffset) {
				G += parseInt(o.curCSS(E, "marginTop", true), 10) || 0,
						F += parseInt(o.curCSS(E, "marginLeft", true), 10) || 0
			}
			return {
				top :G,
				left :F
			}
		}
	};
	o.fn
			.extend( {
				position : function() {
					var I = 0, H = 0, F;
					if (this[0]) {
						var G = this.offsetParent(), J = this.offset(), E = /^body|html$/i
								.test(G[0].tagName) ? {
							top :0,
							left :0
						} : G.offset();
						J.top -= j(this, "marginTop");
						J.left -= j(this, "marginLeft");
						E.top += j(G, "borderTopWidth");
						E.left += j(G, "borderLeftWidth");
						F = {
							top :J.top - E.top,
							left :J.left - E.left
						}
					}
					return F
				},
				offsetParent : function() {
					var E = this[0].offsetParent || document.body;
					while (E
							&& (!/^body|html$/i.test(E.tagName) && o.css(E,
									"position") == "static")) {
						E = E.offsetParent
					}
					return o(E)
				}
			});
	o.each( [ "Left", "Top" ], function(F, E) {
		var G = "scroll" + E;
		o.fn[G] = function(H) {
			if (!this[0]) {
				return null
			}
			return H !== g ? this.each( function() {
				this == l || this == document ? l.scrollTo(!F ? H : o(l)
						.scrollLeft(), F ? H : o(l).scrollTop()) : this[G] = H
			}) : this[0] == l || this[0] == document ? self[F ? "pageYOffset"
					: "pageXOffset"]
					|| o.boxModel
					&& document.documentElement[G]
					|| document.body[G] : this[0][G]
		}
	});
	o.each( [ "Height", "Width" ], function(I, G) {
		var E = I ? "Left" : "Top", H = I ? "Right" : "Bottom", F = G
				.toLowerCase();
		o.fn["inner" + G] = function() {
			return this[0] ? o.css(this[0], F, false, "padding") : null
		};
		o.fn["outer" + G] = function(K) {
			return this[0] ? o.css(this[0], F, false, K ? "margin" : "border")
					: null
		};
		var J = G.toLowerCase();
		o.fn[J] = function(K) {
			return this[0] == l ? document.compatMode == "CSS1Compat"
					&& document.documentElement["client" + G]
					|| document.body["client" + G] : this[0] == document ? Math
					.max(document.documentElement["client" + G],
							document.body["scroll" + G],
							document.documentElement["scroll" + G],
							document.body["offset" + G],
							document.documentElement["offset" + G])
					: K === g ? (this.length ? o.css(this[0], J) : null) : this
							.css(J, typeof K === "string" ? K : K + "px")
		}
	})
})();;
var fotoType = [ '', 'v_', 'n_', 'm_', 's_' ];
$(document)
		.ready(
				function() {
					$('.js_prihlasenie')
							.attr("onclick",
									"this.href = this.href + &hashParam + location.hash; return true;");
					$('div.c_informacia > p.css_xkruh > a').live('click',
							hideInfoBox);
					$('a.login').click(showLoginInfo).live('click',
							showLoginInfo);
				});
var spinner = {
	show : function(pElm) {
		var l = parseInt(pElm.offset().left)
				+ parseInt(pElm.css('paddingLeft'));
		var t = parseInt(pElm.offset().top) + parseInt(pElm.css('paddingTop'));
		var h = pElm.height();
		var w = pElm.width();
		var obj = $('#spinnerDiv');
		if (obj.length < 1)
			obj = $(document.createElement('div')).attr('id', 'spinnerDiv')
					.addClass('c_pokryvac').hide().appendTo($(document.body));
		obj.css( {
			'width' :w,
			'height' :h,
			'left' :l,
			'top' :t,
			'position' :'absolute'
		}).show();
		$('#spinnerImg').show();
		return obj;
	},
	hide : function() {
		$('#spinnerDiv').hide();
		$('#spinnerImg').hide();
	}
}
function azetMsgBox(type, params, callback) {
	var defaults = {
		title :'',
		text :'',
		css :'',
		style :'',
		yesButton :'Ok',
		noButton :'Zru�i�',
		okButton :'Ok',
		info :'',
		params :null
	}
	params = $.extend(defaults, params);
	var isIE6 = ($.browser.msie && $.browser.version == '6.0');
	var obal = document.createElement('div');
	obal._callback = callback;
	obal._close = azetMsgBox_close;
	obal._canClose = true;
	obal._onClose = azetMsgBox_onClose;
	obal._value = false;
	obal._params = params['params'];
	obal.className = 'c_zrnko css_hover c_fixne azmsgbox';
	var jqObal = $(obal);
	jqObal
			.html('<div class="c_konvexnyobal"></div><div class="c_jadro"></div><p class="css_xkruh"><a title="Zavri okno" href="">�</a></p>');
	var jqJadro = jqObal.find('> div.c_jadro');
	$(document.body).append(
			'<div class="c_pokryvac" style="width:' + $(document).width()
					+ 'px;height:' + $(document).height() + 'px"></div>');
	$(document.body).append(obal);
	if (params.css != '')
		jqJadro.addClass(params.css);
	if (params.style != '')
		jqJadro.attr('style', params.style);
	var contentHtml = '';
	if (params.title != '')
		contentHtml += '<h2>' + params.title + '</h2>';
	contentHtml += '<div class="c_form">';
	if (params.text != '')
		contentHtml += '<fieldset class="c_fieldset">' + params.text + '</fieldset>';
	if (type != null && type != '') {
		contentHtml += '<fieldset class="c_akciasoknom c_fieldset">';
		if (type == 'alert') {
			contentHtml += '<button class="c_potvrd c_submit btn_close" type="button">' + params.okButton + '</button>';
		} else if (type == 'confirm') {
			contentHtml += '<button class="c_potvrd c_submit btn_ano" type="button">'
					+ params.yesButton
					+ '</button><a title="Zavrie� okno" class="c_zrus c_submit btn_nie" href="">'
					+ params.noButton + '</a>';
		} else if (type == 'custom') {
			contentHtml += params['buttons'];
		}
		if (params.info.length > 0) {
			contentHtml += '<div class="c_otaznik"><span class="c_text">?</span><span class="c_obr"></span><div class="c_balon c_vpravo"><div class="c_helium"><p>' + params.info + '</p></div><span class="c_dzindzik"></span></div></div>';
		}
		contentHtml += '</fieldset>';
	}
	contentHtml += '</div>';
	$(document).keyup(azetMsgBox_escKey);
	$('body > div.c_pokryvac').click(azetMsgBox_click);
	jqJadro.click(azetMsgBox_click2).append(contentHtml).find('.btn_close')
			.click( function() {
				this.exit();
				return false;
			}).end().find('.btn_ano').click( function() {
				this.setValue(true);
				this.exit();
				return false;
			}).end().find('.btn_nie').click( function() {
				this.setValue(false);
				this.exit();
				return false;
			});
	jqObal.find('div.css_td').hover(azetMsgBox_mouseOver, azetMsgBox_mouseOut)
			.end().find('p.css_xkruh > a').click( function() {
				this.exit();
				return false;
			}).end().find('*').each( function() {
				this.exit = azetMsgBox_forceClose;
				this.setValue = azetMsgBox_setValue;
			});
	var w = parseInt(jqJadro.width());
	jqObal.css('marginLeft', Math.round(w / -2));
	jqJadro.show();
	if (type == 'alert') {
		jqJadro.find('.btn_close').focus();
	} else if (type == 'confirm') {
		jqJadro.find('.btn_nie').focus();
	}
	if (isIE6) {
		$('select').addClass('msghdsel').css('visibility', 'hidden');
	}
	return jqObal;
}
function azetMsgBox_mouseOver() {
	$(this).addClass('css_hover');
}
function azetMsgBox_mouseOut() {
	$(this).removeClass('css_hover');
}
function azetMsgBox_escKey(event) {
	if (event.keyCode == 27)
		$('.azmsgbox p.css_xkruh > a').click();
	if (event.keyCode == 13)
		$('.azmsgbox .btn_ano').click();
}
function azetMsgBox_click() {
	$('body > div.azmsgbox')[0]._close();
}
function azetMsgBox_click2(e) {
	e.stopPropagation();
}
function azetMsgBox_close() {
	var dontClose = this._onClose();
	if (!dontClose) {
		$('body > div.c_pokryvac').remove();
		$(this).fadeOut('fast', function() {
			$(this).remove();
		});
	}
}
function azetMsgBox_onClose() {
	var dontClose = false;
	if (typeof this._callback == 'function') {
		if (this._params != null) {
			dontClose = this._callback(this._value, this._params);
		} else {
			dontClose = this._callback(this._value);
		}
	}
	$('select.msghdsel').css('visibility', 'visible');
	return dontClose;
}
function azetMsgBox_forceClose() {
	$(this).parents('div.azmsgbox')[0]._close();
}
function azetMsgBox_setValue(val) {
	var prnt = $(this).parents('div.azmsgbox')[0];
	if (typeof val != 'undefined')
		prnt._value = val;
	return prnt._value;
}
function getLastLogin(status, sex) {
	if (status['isChating']) {
		if (i9 != '') {
			sRoomLink = aLinks['pokecMiestnost'] + '?i9=' + i9 + '&lok='
					+ status['idChat'];
			sAddClass = '';
		} else {
			sRoomLink = '#';
			sAddClass = ' login';
		}
		return '<a class="c_status'
				+ sAddClass
				+ '" href="'
				+ sRoomLink
				+ '" title="Cho� do miestnosti a Pokecaj s '
				+ (typeof (sex) == 'undefined' ? 'n�m/�ou' : (sex != 2 ? 'n�m'
						: '�ou')) + '"><span class="c_lakmus"></span>'
				+ status['roomName'] + '</a>';
	} else if (status['isLoged']) {
		return '<span class="c_lakmus"></span>' + (typeof (sex) == 'undefined'
				|| sex != 2 ? 'prihl�sen�' : 'prihl�sen�');
	} else {
		var date = new Date();
		date.setTime(status['lastLogin'] * 1000);
		var hrs = date.getHours() < 10 ? '0' + date.getHours() : date
				.getHours();
		var min = date.getMinutes() < 10 ? '0' + date.getMinutes() : date
				.getMinutes();
		return '<span class="c_lakmus"></span>' + date.getDate() + '. '
				+ (date.getMonth() + 1) + '. ' + date.getFullYear() + ', '
				+ hrs + ':' + min;
	}
}
function makeShortText(text, length) {
	if (text.length <= length)
		return text;
	var words = text.split(' ');
	if (words.length < 2)
		return (text.substr(0, length) + '&hellip;');
	var ret = '';
	var pos = 0;
	while ((pos < words.length) && ((ret.length + words[pos].length) < length)) {
		ret += words[pos] + ' ';
		pos++;
	}
	if (pos < words.length)
		ret += '&hellip;';
	return ret;
}
function addGoCookie(pAkcia) {
	var expdate = new Date();
	expdate.setTime(expdate.getTime() + 1000 * 5);
	document.cookie = 'go_' + idUser + "=" + pAkcia + "; expires="
			+ expdate.toGMTString() + "; path=/; domain="
			+ getCookieDomain(window.location.hostname);
}
function openRp(idUser, nick, type) {
	sType = '';
	if (type != '') {
		sType = type + '/' + nick;
	}
	if (useOldRp == 1) {
		Param = 3;
		AddParam = '';
		if (type == 'archiv-rp') {
			Param = 5;
		} else if (type == 'archiv-pokecu') {
			Param = 6;
		} else if (type == 'nova-rp') {
			Param = 3;
			AddParam = '&dalsiaRP=103&AZetID=' + nick;
		} else if (type == 'nastavenia') {
			Param = 14;
		} else if ((type == 'citaj-rp') || (type == 'citaj-rp-auto')) {
			AddParam = '&odpNova=1';
		}
		window
				.open(
						getAzetLink('rp_old', {}) + "index.phtml?i9=" + i9
								+ "&rpI=" + Param + AddParam,
						"RychlaPosta" + Math.floor(Math.random() * 1000000),
						"top=50,left=50,width=535,height=410,buttons=No,scrollbars=Yes,location=No,menubar=No,resizable=No,status=No,directories=No,toolbar=No,copyhistory=No");
	} else {
		addGoCookie(type + '/');
		var rpWindow = window
				.open(
						aLinks['pokec_domain'] + getAzetLink('rp', {
							action :"start"
						}) + '#' + sType,
						'RP' + idUser,
						'top=50,left=50,width=704,height=510,buttons=No,scrollbars=No,location=No,menubar=No,resizable=No,status=No,directories=No,toolbar=No,copyhistory=No');
		if (rpWindow) {
			rpWindow.focus();
		}
	}
	return false;
}
function openPlusActivate(ref) {
	var plusWindow = window
			.open(getAzetLink('plus_links', {
				action :"aktivuj_si_plus"
			}) + "&ref=" + ref, "profil",
					"width=576, height=445, status=0, menubar=0, resizable=0,location=0, toolbar=0");
	if (plusWindow)
		plusWindow.focus();
}
function openPLUSdar(id) {
	window
			.open(
					getAzetLink('plus_links', {
						action :"daruj_plus_popup"
					}) + "&id=" + id,
					"plusDarPopup",
					"top=50,left=50,width=576,height=445,buttons=No,scrollbars=No,location=No,menubar=No,resizable=Yes,status=No,directories=No,toolbar=no,copyhistory=No");
	return false;
}
function OtvorPomoc(url, site) {
	window
			.open(
					url + "&idH=" + site,
					"JavaScriptIt",
					"top=200,left=400,width=530,height=330,buttons=No,scrollbars=Yes,location=No,menubar=No,resizable=Yes,status=Yes,directories=No,toolbar=No,copyhistory=No");
	return false;
}
function superKalendar(button, target, prefix, initPeriod, hilightInitDate) {
	$(button)
			.click(
					function() {
						if (typeof superKalendarDraw !== "function") {
							$("head")
									.append(
											"<link rel=\"stylesheet\" href=\""
													+ Config["url_common_css"]
													+ "superKalendar.css?v20090823\" type=\"text/css\" media=\"screen\">");
							$.getScript(Config["url_common_js"] + "date.js",
									function() {
										$.getScript(Config["url_common_js"]
												+ "superKalendar.js?v20090823",
												function() {
													superKalendarDraw(button,
															target, prefix,
															initPeriod,
															hilightInitDate);
												});
									});
						} else {
							superKalendarDraw(button, target, prefix,
									initPeriod, hilightInitDate);
						}
						return false;
					});
}
function getAzetLink(linkName, params) {
	var link = aLinks[linkName];
	if (typeof link == 'undefined')
		return false;
	if (typeof params == 'undefined')
		params = {};
	var patt = new RegExp('PARAM_([a-zA-Z0-9]{1,})', 'gi');
	var matches = link.match(patt);
	if (matches == null)
		return link;
	var key, val;
	for ( var i = 0; i < matches.length; i++) {
		key = matches[i].replace(/PARAM_/g, '');
		if (typeof params[key] != 'undefined') {
			val = params[key];
		} else if (typeof window[key] != 'undefined') {
			val = window[key];
		} else {
			val = '';
		}
		link = link.replace(matches[i], val);
	}
	link = link.replace(/([^:]{1})\/\/([$?])/gi, '$1$2').replace(/i9=(&|$)/gi,
			'').replace(/[?]$/gi, '');
	return link;
}
function composeUserPhotoLink(sLink) {
	return sLink.replace('PARAM_p', 'http://').replace('PARAM_ext', '.jpg');
}
function normalizeUserData(user, dopInfo) {
	if (typeof user['isFriend'] == 'undefined')
		user['isFriend'] = false;
	if (dopInfo['allowDel'] == null || dopInfo['allowDel'] == false) {
		user['allowDel'] = false;
	} else {
		user['allowDel'] = true;
	}
	user['age'] = (user['age'] == '-' ? '?' : user['age']);
	if (dopInfo['makeEmphasis'] != null && dopInfo['makeEmphasis'] == true
			&& user['emphasis'] != null && user['emphasis'] == true) {
		user['makeEmphasis'] = true;
	} else {
		user['makeEmphasis'] = false;
	}
	if (typeof user['STATUS_MESSAGE'] == 'undefined')
		user['STATUS_MESSAGE'] = {};
	if (typeof user['STATUS_MESSAGE']['statusMessage'] == 'undefined')
		user['STATUS_MESSAGE']['statusMessage'] = '';
	if (typeof user['NAME'] == 'undefined')
		user['NAME'] = {
			fullName :'',
			firstName :'',
			surName :''
		};
	if (typeof user['nick'] == 'undefined')
		user['nick'] = '&nbsp';
	if (typeof user['sefNick'] == 'undefined')
		user['sefNick'] = user['nick'].toLowerCase();
	user['FUNC'] = {};
	if (idUser > 0) {
		user['FUNC']['onClAddFriend'] = 'afAddFriend({nick:\'' + user['nick'] + '\'});return false;';
		user['FUNC']['onClAddPlus'] = 'openPLUSdar(' + user['idUser'] + ');return false;';
		user['FUNC']['onClPosliRp'] = 'openRp(' + idUser + ',\'' + user['nick']
				+ '\',\'nova-rp\');return false;';
	} else {
		user['FUNC']['onClAddFriend'] = user['FUNC']['onClAddPlus'] = user['FUNC']['onClPosliRp'] = 'azetPrihlasenie()';
	}
	return user;
}
function makeVizitka(user, dopInfo) {
	user = normalizeUserData(user, dopInfo);
	html = '<div class="c_bublitka '
			+ ((user['isBlocked'] || user['isDeleted']) ? 'c_herma'
					: (user['sex'] != 2 ? 'c_muz' : 'c_zena'))
			+ (user['makeEmphasis'] == true ? ' c_zvyrazneny' : '') + '" tag="'
			+ user['idUser'] + '">';
	html += '<div class="c_info">';
	if (!user['isBlocked'] && !user['isDeleted']) {
		html += '<h2><a href="' + getAzetLink('profil', {
			nick :user['sefNick']
		}) + '">' + user['nick'] + '</a></h2>';
		if (user['STATUS_MESSAGE']['statusMessage'].length > 44) {
			html += '<q title="' + user['STATUS_MESSAGE']['statusMessage']
					+ '">'
					+ user['STATUS_MESSAGE']['statusMessage'].substr(0, 42)
					+ '&hellip;' + '</q>';
		} else {
			html += '<q>' + user['STATUS_MESSAGE']['statusMessage'] + '</q>';
		}
		if (user['NAME']['fullName'].length > 22) {
			html += '<h3 title="' + user['NAME']['fullName'] + '">'
					+ user['NAME']['fullName'].substr(0, 20) + '&hellip;'
					+ '</h3>';
		} else {
			html += '<h3>' + user['NAME']['fullName'] + '</h3>';
		}
		html += '<p>' + user['LOCATION']['cityName'] + '<br/>'
				+ getLastLogin(user['STATUS'], user['sex']) + '</p>';
		html += '<span class="c_vek"><span>' + (user['sex'] != 2 ? 'm' : '�')
				+ '</span> / <span>' + user['age'] + '</span></span>';
		html += '</div>';
		html += '<div class="c_ovladanie">';
		html += '<ul class="c_obsahove">';
		if (user['photoAlbums'] > 0)
			html += '<li class="c_fo"><a title="Fotoalbumy" href="'
					+ getAzetLink('fotoalbumy', {
						nick :user['sefNick']
					})
					+ '"><small>'
					+ user['photoAlbums']
					+ '</small><span class="c_ikonka"></span><span class="c_text">Fotoalbumy</span></a></li>';
		if (user['videoAlbums'] > 0)
			html += '<li class="c_vi"><a title="Videoalbumy" href="'
					+ getAzetLink('videoalbumy', {
						nick :user['sefNick']
					})
					+ '"><small>'
					+ user['videoAlbums']
					+ '</small><span class="c_ikonka"></span><span class="c_text">Videoalbumy</span></a></li>';
		html += '</ul>';
		html += '<ul class="c_pouzivatelske">';
		html += '<li class="c_rp">';
		if (typeof idUser == 'undefined' || idUser == 0) {
			html += '<a title="Po�li RP" class="login" href="#">';
		} else {
			html += '<a title="Po�li RP" onClick="' + user['FUNC']['onClPosliRp'] + '">';
		}
		html += ' <span class="c_ikonka"></span><span class="c_text">RP</span></a></li>';
		if (user['isFriend'] == false) {
			html += '<li class="c_pr">';
			if (typeof idUser == 'undefined' || idUser == 0) {
				html += '<a title="Pridaj k priate�om" href="#" class="login">';
			} else {
				html += '<a title="Pridaj k priate�om" href="#" onClick="' + user['FUNC']['onClAddFriend'] + '">';
			}
			html += ' <span class="c_ikonka"></span><span class="c_text">Spriateli� sa</span></a></li>';
		}
		if (typeof user['noAddGift'] == 'undefined'
				|| user['noAddGift'] == false) {
			html += '<li class="c_pl">';
			if (typeof idUser == 'undefined' || idUser == 0) {
				html += '<a title="Daruj Plus" href="#" class="login">';
			} else {
				html += '<a title="Daruj Plus" href="#" onClick="' + user['FUNC']['onClAddPlus'] + '">';
			}
			html += ' <span class="c_ikonka"></span><span class="c_text">Daruj Plus</span></a></li>';
		}
		html += '</ul>';
		html += '</div>';
		html += '<a class="c_fotka" href="' + getAzetLink('profil', {
			nick :user['sefNick']
		}) + '" title="' + user['nick'] + '">';
		if (typeof user['PHOTO'] != 'undefined'
				&& typeof user['PHOTO']['normal'] == 'string') {
			html += '<img src="' + composeUserPhotoLink(user['PHOTO']['normal']) + '" width="88" height="110" alt=""></a>';
		} else {
			html += '</a>';
		}
	} else {
		html += '<h2>' + user['nick'] + '</h2><q></q><h3></h3>';
		html += '<p>';
		if (user['isBlocked']) {
			html += 'Zablokovan� pou��vate�.<br /><b>D�vod:</b> poru�ovanie pravidiel.';
		} else {
			html += 'Zmazan� pou��vate�.<br />Nick m��e pou��va� niekto in�.';
		}
		html += '</p><span class="c_vek"></span></div><div class="c_ovladanie"></div><a class="c_fotka" href="#"></a>';
	}
	if (user['allowDel'])
		html += '<p class="css_xkruh"><a title="Zmaza� z priate�ov" href=""><span>&times;</span></a></p>';
	html += '</div>';
	return html;
}
function makeKarta(user, dopInfo) {
	user = normalizeUserData(user, dopInfo);
	var profil = getAzetLink('profil', {
		nick :user['sefNick']
	});
	html = '<div class="css_karta '
			+ ((user['isBlocked'] || user['isDeleted']) ? 'css_herma'
					: (user['sex'] != 2 ? 'css_muz' : 'css_zena'))
			+ (user['makeEmphasis'] == true ? ' c_zvyrazneny' : '');
	html += '" onmouseover="$(this).addClass(\'css_hover\');" onmouseout="$(this).removeClass(\'css_hover\');" tag="' + user['idUser'] + '">';
	if (!user['isBlocked'] && !user['isDeleted']) {
		html += '<p class="css_foto"><a href="' + profil + '" title="'
				+ user['nick'] + '"><span>';
		if (typeof user['PHOTO'] != 'undefined'
				&& typeof user['PHOTO']['normal'] == 'string') {
			html += '<img src="'
					+ composeUserPhotoLink(user['PHOTO']['normal'])
					+ '" width="88" height="110" alt="' + user['nick'] + '">';
		}
		html += '</a></span></p>';
		html += '<span class="css_vek" title="Vek">' + user['age'] + '</span>';
		html += '<p class="css_nick"><a href="' + profil + '">' + user['nick']
				+ '</a></p>';
		html += '<ul class="css_ovladace">';
		html += '<li class="css_poslirp">';
		if (typeof idUser == 'undefined' || idUser == 0) {
			html += '<a title="Po�li RP" class="login" href="#">';
		} else {
			html += '<a title="Po�li RP" href="#" onClick="' + user['FUNC']['onClPosliRp'] + '">';
		}
		html += '</a></li>';
		if (user['isFriend'] == false) {
			html += '<li class="css_dopriatelov">';
			if (typeof idUser == 'undefined' || idUser == 0) {
				html += '<a title="Pridaj k priate�om" href="#" class="login">';
			} else {
				html += '<a title="Pridaj k priate�om" href="#" onClick="' + user['FUNC']['onClAddFriend'] + '">';
			}
			html += '</a></li>';
		} else {
			html += '<li class=""><a></a></li>';
		}
		if (typeof user['noAddGift'] == 'undefined'
				|| user['noAddGift'] == false) {
			html += '<li class="css_darujplus">';
			if (typeof idUser == 'undefined' || idUser == 0) {
				html += '<a title="Daruj Plus" href="#" class="login">';
			} else {
				html += '<a title="Daruj Plus" href="#" onClick="' + user['FUNC']['onClAddPlus'] + '">';
			}
			html += '</a></li>';
		}
		html += '</ul>';
	} else {
		html += '<p class="css_foto"><a href="#"><span></span></a></p>';
		html += '<span class="css_vek" ' + (user['isBlocked'] ? 'title="Pou��vate� je zablokovan� z d�vodu poru�ovania pravidiel.">Zablokovan�'
				: 'title="Pou��vate� je zmazan�. Nick m��e pou��va� niekto in�." >Zmazan�') + '</span>';
		html += '<p class="css_nick"><a href="#">' + user['nick'] + '</a></p>';
	}
	html += '</div>';
	return html;
}
function azetPrihlasenie() {
	location.assign(getAzetLink('prihlasenie', {
		uri :encodeURIComponent(location.href)
	}));
}
jQuery.fn.flexiTextarea = function() {
	return this
			.each( function() {
				$(this)
						.keyup(
								function() {
									if (!this._height)
										this._height = $(this).height();
									var h = parseInt(this._height);
									$(this).height(h);
									var adjustedHeight = this.clientHeight;
									adjustedHeight = Math.max(
											this.scrollHeight, adjustedHeight);
									if (adjustedHeight > 500) {
										$(this).height(500).unbind('keyup');
									} else {
										$(this)
												.height(
														(adjustedHeight > this.clientHeight ? (adjustedHeight + 4)
																: h));
									}
								});
			});
}
jQuery.fn.maxLength = function(len) {
	return this.each( function() {
		$(this).keyup( function() {
			var val = $(this).val();
			if (val.length > len)
				$(this).val(val.substr(0, len));
		});
	});
}
function getCookieDomain(url) {
	var urlArr = url.split('.');
	var outUrl = '';
	for ( var i = urlArr.length; i > (urlArr.length - 2); i--) {
		outUrl = '.' + urlArr[i - 1] + outUrl;
	}
	return outUrl;
}
function hideInfoBox() {
	var box = $(this).parents('div.c_informacia').slideUp('fast');
	$.ajax( {
		type :'POST',
		url :getAzetLink('sluzby_system', {
			action :'vypni-info-text'
		}),
		data :'idInfoText=' + box.attr('tag')
	});
	return false;
}
function cennikSms(data) {
	if (typeof data == 'undefined') {
		$.get(getAzetLink('plus_links', {
			action :'cennik_sms'
		}), cennikSms);
		return false;
	}
	azetMsgBox(null, {
		title :"",
		text :data,
		style :"width: 600px;"
	});
}
function showLoginInfo(e) {
	var url = $(this).attr('href');
	if (url == undefined || $.trim(url).length == 0 || url == '#') {
		url = location.href;
	} else if (url.search(/^#/) == 0) {
		url = location.protocol + '//' + location.hostname + location.pathname
				+ url
	} else if (url.indexOf('http://') == -1) {
		url = location.protocol + '//' + location.hostname + url;
	}
	params = {
		title :'Prihl�senie',
		text :'<input type="hidden" id="forceLoginURL" value="' + encodeURIComponent(url) + '" /><div class="c_vyrazny">T�to akcia vy�aduje prihl�senie. Chce� sa prihl�si�?</div>',
		yesButton :'�no',
		noButton :'Nie',
		style :'width:350px'
	};
	azetMsgBox('confirm', params, forceToLogin);
	e.stopImmediatePropagation();
	return false;
}
function forceToLogin(ret) {
	if (ret)
		location.href = getAzetLink('prihlasenie', {
			uri :$('#forceLoginURL').val()
		});
};
( function($) {
	$.Jcrop = function(obj, opt) {
		var obj = obj, opt = opt;
		if (typeof (obj) !== 'object')
			obj = $(obj)[0];
		if (typeof (opt) !== 'object')
			opt = {};
		if (!('trackDocument' in opt)) {
			opt.trackDocument = $.browser.msie ? false : true;
			if ($.browser.msie && $.browser.version.split('.')[0] == '8')
				opt.trackDocument = true;
		}
		if (!('keySupport' in opt))
			opt.keySupport = $.browser.msie ? false : true;
		var defaults = {
			trackDocument :false,
			baseClass :'jcrop',
			addClass :null,
			bgColor :'black',
			bgOpacity :.6,
			borderOpacity :.4,
			handleOpacity :.5,
			handlePad :5,
			handleSize :9,
			handleOffset :5,
			edgeMargin :14,
			aspectRatio :0,
			keySupport :true,
			cornerHandles :true,
			sideHandles :true,
			drawBorders :true,
			dragEdges :true,
			boxWidth :0,
			boxHeight :0,
			boundary :8,
			animationDelay :20,
			swingSpeed :3,
			allowSelect :true,
			allowMove :true,
			allowResize :true,
			minSelect : [ 0, 0 ],
			maxSize : [ 0, 0 ],
			minSize : [ 0, 0 ],
			onChange : function() {
			},
			onSelect : function() {
			}
		};
		var options = defaults;
		setOptions(opt);
		var $origimg = $(obj);
		var $img = $origimg.clone().removeAttr('id').css( {
			position :'absolute'
		});
		$img.width($origimg.width());
		$img.height($origimg.height());
		$origimg.after($img).hide();
		presize($img, options.boxWidth, options.boxHeight);
		var boundx = $img.width(), boundy = $img.height(), $div = $('<div />')
				.width(boundx).height(boundy).addClass(cssClass('holder')).css(
						{
							position :'relative',
							backgroundColor :options.bgColor
						}).insertAfter($origimg).append($img);
		;
		if (options.addClass)
			$div.addClass(options.addClass);
		var $img2 = $('<img />').attr('src', $img.attr('src')).css('position',
				'absolute').width(boundx).height(boundy);
		var $img_holder = $('<div />').width(pct(100)).height(pct(100)).css( {
			zIndex :310,
			position :'absolute',
			overflow :'hidden'
		}).append($img2);
		var $hdl_holder = $('<div />').width(pct(100)).height(pct(100)).css(
				'zIndex', 320);
		var $sel = $('<div />').css( {
			position :'absolute',
			zIndex :300
		}).insertBefore($img).append($img_holder, $hdl_holder);
		var bound = options.boundary;
		var $trk = newTracker().width(boundx + (bound * 2)).height(
				boundy + (bound * 2)).css( {
			position :'absolute',
			top :px(-bound),
			left :px(-bound),
			zIndex :290
		}).mousedown(newSelection);
		var xlimit, ylimit, xmin, ymin;
		var xscale, yscale, enabled = true;
		var docOffset = getPos($img), btndown, lastcurs, dimmed, animating, shift_down;
		var Coords = function() {
			var x1 = 0, y1 = 0, x2 = 0, y2 = 0, ox, oy;
			function setPressed(pos) {
				var pos = rebound(pos);
				x2 = x1 = pos[0];
				y2 = y1 = pos[1];
			}
			;
			function setCurrent(pos) {
				var pos = rebound(pos);
				ox = pos[0] - x2;
				oy = pos[1] - y2;
				x2 = pos[0];
				y2 = pos[1];
			}
			;
			function getOffset() {
				return [ ox, oy ];
			}
			;
			function moveOffset(offset) {
				var ox = offset[0], oy = offset[1];
				if (0 > x1 + ox)
					ox -= ox + x1;
				if (0 > y1 + oy)
					oy -= oy + y1;
				if (boundy < y2 + oy)
					oy += boundy - (y2 + oy);
				if (boundx < x2 + ox)
					ox += boundx - (x2 + ox);
				x1 += ox;
				x2 += ox;
				y1 += oy;
				y2 += oy;
			}
			;
			function getCorner(ord) {
				var c = getFixed();
				switch (ord) {
				case 'ne':
					return [ c.x2, c.y ];
				case 'nw':
					return [ c.x, c.y ];
				case 'se':
					return [ c.x2, c.y2 ];
				case 'sw':
					return [ c.x, c.y2 ];
				}
			}
			;
			function getFixed() {
				if (!options.aspectRatio)
					return getRect();
				var aspect = options.aspectRatio, min_x = options.minSize[0]
						/ xscale, min_y = options.minSize[1] / yscale, max_x = options.maxSize[0]
						/ xscale, max_y = options.maxSize[1] / yscale, rw = x2
						- x1, rh = y2 - y1, rwa = Math.abs(rw), rha = Math
						.abs(rh), real_ratio = rwa / rha, xx, yy;
				if (max_x == 0) {
					max_x = boundx * 10
				}
				if (max_y == 0) {
					max_y = boundy * 10
				}
				if (real_ratio < aspect) {
					yy = y2;
					w = rha * aspect;
					xx = rw < 0 ? x1 - w : w + x1;
					if (xx < 0) {
						xx = 0;
						h = Math.abs((xx - x1) / aspect);
						yy = rh < 0 ? y1 - h : h + y1;
					} else if (xx > boundx) {
						xx = boundx;
						h = Math.abs((xx - x1) / aspect);
						yy = rh < 0 ? y1 - h : h + y1;
					}
				} else {
					xx = x2;
					h = rwa / aspect;
					yy = rh < 0 ? y1 - h : y1 + h;
					if (yy < 0) {
						yy = 0;
						w = Math.abs((yy - y1) * aspect);
						xx = rw < 0 ? x1 - w : w + x1;
					} else if (yy > boundy) {
						yy = boundy;
						w = Math.abs(yy - y1) * aspect;
						xx = rw < 0 ? x1 - w : w + x1;
					}
				}
				if (xx > x1) {
					if (xx - x1 < min_x) {
						xx = x1 + min_x;
					} else if (xx - x1 > max_x) {
						xx = x1 + max_x;
					}
					if (yy > y1) {
						yy = y1 + (xx - x1) / aspect;
					} else {
						yy = y1 - (xx - x1) / aspect;
					}
				} else if (xx < x1) {
					if (x1 - xx < min_x) {
						xx = x1 - min_x
					} else if (x1 - xx > max_x) {
						xx = x1 - max_x;
					}
					if (yy > y1) {
						yy = y1 + (x1 - xx) / aspect;
					} else {
						yy = y1 - (x1 - xx) / aspect;
					}
				}
				if (xx < 0) {
					x1 -= xx;
					xx = 0;
				} else if (xx > boundx) {
					x1 -= xx - boundx;
					xx = boundx;
				}
				if (yy < 0) {
					y1 -= yy;
					yy = 0;
				} else if (yy > boundy) {
					y1 -= yy - boundy;
					yy = boundy;
				}
				return last = makeObj(flipCoords(x1, y1, xx, yy));
			}
			;
			function rebound(p) {
				if (p[0] < 0)
					p[0] = 0;
				if (p[1] < 0)
					p[1] = 0;
				if (p[0] > boundx)
					p[0] = boundx;
				if (p[1] > boundy)
					p[1] = boundy;
				return [ p[0], p[1] ];
			}
			;
			function flipCoords(x1, y1, x2, y2) {
				var xa = x1, xb = x2, ya = y1, yb = y2;
				if (x2 < x1) {
					xa = x2;
					xb = x1;
				}
				if (y2 < y1) {
					ya = y2;
					yb = y1;
				}
				return [ Math.round(xa), Math.round(ya), Math.round(xb),
						Math.round(yb) ];
			}
			;
			function getRect() {
				var xsize = x2 - x1;
				var ysize = y2 - y1;
				if (xlimit && (Math.abs(xsize) > xlimit))
					x2 = (xsize > 0) ? (x1 + xlimit) : (x1 - xlimit);
				if (ylimit && (Math.abs(ysize) > ylimit))
					y2 = (ysize > 0) ? (y1 + ylimit) : (y1 - ylimit);
				if (ymin && (Math.abs(ysize) < ymin))
					y2 = (ysize > 0) ? (y1 + ymin) : (y1 - ymin);
				if (xmin && (Math.abs(xsize) < xmin))
					x2 = (xsize > 0) ? (x1 + xmin) : (x1 - xmin);
				if (x1 < 0) {
					x2 -= x1;
					x1 -= x1;
				}
				if (y1 < 0) {
					y2 -= y1;
					y1 -= y1;
				}
				if (x2 < 0) {
					x1 -= x2;
					x2 -= x2;
				}
				if (y2 < 0) {
					y1 -= y2;
					y2 -= y2;
				}
				if (x2 > boundx) {
					var delta = x2 - boundx;
					x1 -= delta;
					x2 -= delta;
				}
				if (y2 > boundy) {
					var delta = y2 - boundy;
					y1 -= delta;
					y2 -= delta;
				}
				if (x1 > boundx) {
					var delta = x1 - boundy;
					y2 -= delta;
					y1 -= delta;
				}
				if (y1 > boundy) {
					var delta = y1 - boundy;
					y2 -= delta;
					y1 -= delta;
				}
				return makeObj(flipCoords(x1, y1, x2, y2));
			}
			;
			function makeObj(a) {
				return {
					x :a[0],
					y :a[1],
					x2 :a[2],
					y2 :a[3],
					w :a[2] - a[0],
					h :a[3] - a[1]
				};
			}
			;
			return {
				flipCoords :flipCoords,
				setPressed :setPressed,
				setCurrent :setCurrent,
				getOffset :getOffset,
				moveOffset :moveOffset,
				getCorner :getCorner,
				getFixed :getFixed
			};
		}();
		var Selection = function() {
			var start, end, dragmode, awake, hdep = 370;
			var borders = {};
			var handle = {};
			var seehandles = false;
			var hhs = options.handleOffset;
			if (options.drawBorders) {
				borders = {
					top :insertBorder('hline').css('top',
							$.browser.msie ? px(-1) : px(0)),
					bottom :insertBorder('hline'),
					left :insertBorder('vline'),
					right :insertBorder('vline')
				};
			}
			if (options.dragEdges) {
				handle.t = insertDragbar('n');
				handle.b = insertDragbar('s');
				handle.r = insertDragbar('e');
				handle.l = insertDragbar('w');
			}
			options.sideHandles && createHandles( [ 'n', 's', 'e', 'w' ]);
			options.cornerHandles && createHandles( [ 'sw', 'nw', 'ne', 'se' ]);
			function insertBorder(type) {
				var jq = $('<div />').css( {
					position :'absolute',
					opacity :options.borderOpacity
				}).addClass(cssClass(type));
				$img_holder.append(jq);
				return jq;
			}
			;
			function dragDiv(ord, zi) {
				var jq = $('<div />').mousedown(createDragger(ord)).css( {
					cursor :ord + '-resize',
					position :'absolute',
					zIndex :zi
				});
				$hdl_holder.append(jq);
				return jq;
			}
			;
			function insertHandle(ord) {
				return dragDiv(ord, hdep++).css( {
					top :px(-hhs + 1),
					left :px(-hhs + 1),
					opacity :options.handleOpacity
				}).addClass(cssClass('handle'));
			}
			;
			function insertDragbar(ord) {
				var s = options.handleSize, o = hhs, h = s, w = s, t = o, l = o;
				switch (ord) {
				case 'n':
				case 's':
					w = pct(100);
					break;
				case 'e':
				case 'w':
					h = pct(100);
					break;
				}
				return dragDiv(ord, hdep++).width(w).height(h).css( {
					top :px(-t + 1),
					left :px(-l + 1)
				});
			}
			;
			function createHandles(li) {
				for (i in li)
					handle[li[i]] = insertHandle(li[i]);
			}
			;
			function moveHandles(c) {
				var midvert = Math.round((c.h / 2) - hhs), midhoriz = Math
						.round((c.w / 2) - hhs), north = west = -hhs + 1, east = c.w
						- hhs, south = c.h - hhs, x, y;
				'e' in handle && handle.e.css( {
					top :px(midvert),
					left :px(east)
				}) && handle.w.css( {
					top :px(midvert)
				}) && handle.s.css( {
					top :px(south),
					left :px(midhoriz)
				}) && handle.n.css( {
					left :px(midhoriz)
				});
				'ne' in handle && handle.ne.css( {
					left :px(east)
				}) && handle.se.css( {
					top :px(south),
					left :px(east)
				}) && handle.sw.css( {
					top :px(south)
				});
				'b' in handle && handle.b.css( {
					top :px(south)
				}) && handle.r.css( {
					left :px(east)
				});
			}
			;
			function moveto(x, y) {
				$img2.css( {
					top :px(-y),
					left :px(-x)
				});
				$sel.css( {
					top :px(y),
					left :px(x)
				});
			}
			;
			function resize(w, h) {
				$sel.width(w).height(h);
			}
			;
			function refresh() {
				var c = Coords.getFixed();
				Coords.setPressed( [ c.x, c.y ]);
				Coords.setCurrent( [ c.x2, c.y2 ]);
				updateVisible();
			}
			;
			function updateVisible() {
				if (awake)
					return update();
			}
			;
			function update() {
				var c = Coords.getFixed();
				resize(c.w, c.h);
				moveto(c.x, c.y);
				options.drawBorders && borders['right'].css( {
					left :px(c.w - 1)
				}) && borders['bottom'].css( {
					top :px(c.h - 1)
				});
				seehandles && moveHandles(c);
				awake || show();
				options.onChange(unscale(c));
			}
			;
			function show() {
				$sel.show();
				$img.css('opacity', options.bgOpacity);
				awake = true;
			}
			;
			function release() {
				disableHandles();
				$sel.hide();
				$img.css('opacity', 1);
				awake = false;
			}
			;
			function showHandles() {
				if (seehandles) {
					moveHandles(Coords.getFixed());
					$hdl_holder.show();
				}
			}
			;
			function enableHandles() {
				seehandles = true;
				if (options.allowResize) {
					moveHandles(Coords.getFixed());
					$hdl_holder.show();
					return true;
				}
			}
			;
			function disableHandles() {
				seehandles = false;
				$hdl_holder.hide();
			}
			;
			function animMode(v) {
				(animating = v) ? disableHandles() : enableHandles();
			}
			;
			function done() {
				animMode(false);
				refresh();
			}
			;
			var $track = newTracker().mousedown(createDragger('move')).css( {
				cursor :'move',
				position :'absolute',
				zIndex :360
			})
			$img_holder.append($track);
			disableHandles();
			return {
				updateVisible :updateVisible,
				update :update,
				release :release,
				refresh :refresh,
				setCursor : function(cursor) {
					$track.css('cursor', cursor);
				},
				enableHandles :enableHandles,
				enableOnly : function() {
					seehandles = true;
				},
				showHandles :showHandles,
				disableHandles :disableHandles,
				animMode :animMode,
				done :done
			};
		}();
		var Tracker = function() {
			var onMove = function() {
			}, onDone = function() {
			}, trackDoc = options.trackDocument;
			if (!trackDoc) {
				$trk.mousemove(trackMove).mouseup(trackUp).mouseout(trackUp);
			}
			function toFront() {
				$trk.css( {
					zIndex :450
				});
				if (trackDoc) {
					$(document).mousemove(trackMove).mouseup(trackUp);
				}
			}
			function toBack() {
				$trk.css( {
					zIndex :290
				});
				if (trackDoc) {
					$(document).unbind('mousemove', trackMove).unbind(
							'mouseup', trackUp);
				}
			}
			function trackMove(e) {
				onMove(mouseAbs(e));
			}
			;
			function trackUp(e) {
				e.preventDefault();
				e.stopPropagation();
				if (btndown) {
					btndown = false;
					onDone(mouseAbs(e));
					options.onSelect(unscale(Coords.getFixed()));
					toBack();
					onMove = function() {
					};
					onDone = function() {
					};
				}
				return false;
			}
			;
			function activateHandlers(move, done) {
				btndown = true;
				onMove = move;
				onDone = done;
				toFront();
				return false;
			}
			;
			function setCursor(t) {
				$trk.css('cursor', t);
			}
			;
			$img.before($trk);
			return {
				activateHandlers :activateHandlers,
				setCursor :setCursor
			};
		}();
		var KeyManager = function() {
			var $keymgr = $('<input type="radio" />').css( {
				position :'absolute',
				left :'-30px'
			}).keypress(parseKey).blur(onBlur), $keywrap = $('<div />').css( {
				position :'absolute',
				overflow :'hidden'
			}).append($keymgr);
			function watchKeys() {
				if (options.keySupport) {
					$keymgr.show();
					$keymgr.focus();
				}
			}
			;
			function onBlur(e) {
				$keymgr.hide();
			}
			;
			function doNudge(e, x, y) {
				if (options.allowMove) {
					Coords.moveOffset( [ x, y ]);
					Selection.updateVisible();
				}
				;
				e.preventDefault();
				e.stopPropagation();
			}
			;
			function parseKey(e) {
				if (e.ctrlKey)
					return true;
				shift_down = e.shiftKey ? true : false;
				var nudge = shift_down ? 10 : 1;
				switch (e.keyCode) {
				case 37:
					doNudge(e, -nudge, 0);
					break;
				case 39:
					doNudge(e, nudge, 0);
					break;
				case 38:
					doNudge(e, 0, -nudge);
					break;
				case 40:
					doNudge(e, 0, nudge);
					break;
				case 27:
					Selection.release();
					break;
				case 9:
					return true;
				}
				return nothing(e);
			}
			;
			if (options.keySupport)
				$keywrap.insertBefore($img);
			return {
				watchKeys :watchKeys
			};
		}();
		function px(n) {
			return '' + parseInt(n) + 'px';
		}
		;
		function pct(n) {
			return '' + parseInt(n) + '%';
		}
		;
		function cssClass(cl) {
			return options.baseClass + '-' + cl;
		}
		;
		function getPos(obj) {
			var pos = $(obj).offset();
			return [ pos.left, pos.top ];
		}
		;
		function mouseAbs(e) {
			return [ (e.pageX - docOffset[0]), (e.pageY - docOffset[1]) ];
		}
		;
		function myCursor(type) {
			if (type != lastcurs) {
				Tracker.setCursor(type);
				lastcurs = type;
			}
		}
		;
		function startDragMode(mode, pos) {
			docOffset = getPos($img);
			Tracker.setCursor(mode == 'move' ? mode : mode + '-resize');
			if (mode == 'move')
				return Tracker.activateHandlers(createMover(pos), doneSelect);
			var fc = Coords.getFixed();
			var opp = oppLockCorner(mode);
			var opc = Coords.getCorner(oppLockCorner(opp));
			Coords.setPressed(Coords.getCorner(opp));
			Coords.setCurrent(opc);
			Tracker.activateHandlers(dragmodeHandler(mode, fc), doneSelect);
		}
		;
		function dragmodeHandler(mode, f) {
			return function(pos) {
				if (!options.aspectRatio)
					switch (mode) {
					case 'e':
						pos[1] = f.y2;
						break;
					case 'w':
						pos[1] = f.y2;
						break;
					case 'n':
						pos[0] = f.x2;
						break;
					case 's':
						pos[0] = f.x2;
						break;
					}
				else
					switch (mode) {
					case 'e':
						pos[1] = f.y + 1;
						break;
					case 'w':
						pos[1] = f.y + 1;
						break;
					case 'n':
						pos[0] = f.x + 1;
						break;
					case 's':
						pos[0] = f.x + 1;
						break;
					}
				Coords.setCurrent(pos);
				Selection.update();
			};
		}
		;
		function createMover(pos) {
			var lloc = pos;
			KeyManager.watchKeys();
			return function(pos) {
				Coords.moveOffset( [ pos[0] - lloc[0], pos[1] - lloc[1] ]);
				lloc = pos;
				Selection.update();
			};
		}
		;
		function oppLockCorner(ord) {
			switch (ord) {
			case 'n':
				return 'sw';
			case 's':
				return 'nw';
			case 'e':
				return 'nw';
			case 'w':
				return 'ne';
			case 'ne':
				return 'sw';
			case 'nw':
				return 'se';
			case 'se':
				return 'nw';
			case 'sw':
				return 'ne';
			}
			;
		}
		;
		function createDragger(ord) {
			return function(e) {
				if (options.disabled)
					return false;
				if ((ord == 'move') && !options.allowMove)
					return false;
				btndown = true;
				startDragMode(ord, mouseAbs(e));
				e.stopPropagation();
				e.preventDefault();
				return false;
			};
		}
		;
		function presize($obj, w, h) {
			var nw = $obj.width(), nh = $obj.height();
			if ((nw > w) && w > 0) {
				nw = w;
				nh = (w / $obj.width()) * $obj.height();
			}
			if ((nh > h) && h > 0) {
				nh = h;
				nw = (h / $obj.height()) * $obj.width();
			}
			xscale = $obj.width() / nw;
			yscale = $obj.height() / nh;
			$obj.width(nw).height(nh);
		}
		;
		function unscale(c) {
			return {
				x :parseInt(c.x * xscale),
				y :parseInt(c.y * yscale),
				x2 :parseInt(c.x2 * xscale),
				y2 :parseInt(c.y2 * yscale),
				w :parseInt(c.w * xscale),
				h :parseInt(c.h * yscale)
			};
		}
		;
		function doneSelect(pos) {
			var c = Coords.getFixed();
			if (c.w > options.minSelect[0] && c.h > options.minSelect[1]) {
				Selection.enableHandles();
				Selection.done();
			} else {
				Selection.release();
			}
			Tracker.setCursor(options.allowSelect ? 'crosshair' : 'default');
		}
		;
		function newSelection(e) {
			if (options.disabled)
				return false;
			if (!options.allowSelect)
				return false;
			btndown = true;
			docOffset = getPos($img);
			Selection.disableHandles();
			myCursor('crosshair');
			var pos = mouseAbs(e);
			Coords.setPressed(pos);
			Tracker.activateHandlers(selectDrag, doneSelect);
			KeyManager.watchKeys();
			Selection.update();
			e.stopPropagation();
			e.preventDefault();
			return false;
		}
		;
		function selectDrag(pos) {
			Coords.setCurrent(pos);
			Selection.update();
		}
		;
		function newTracker() {
			var trk = $('<div></div>').addClass(cssClass('tracker'));
			$.browser.msie && trk.css( {
				opacity :0,
				backgroundColor :'white'
			});
			return trk;
		}
		;
		function animateTo(a) {
			var x1 = a[0] / xscale, y1 = a[1] / yscale, x2 = a[2] / xscale, y2 = a[3]
					/ yscale;
			if (animating)
				return;
			var animto = Coords.flipCoords(x1, y1, x2, y2);
			var c = Coords.getFixed();
			var animat = initcr = [ c.x, c.y, c.x2, c.y2 ];
			var interv = options.animationDelay;
			var x = animat[0];
			var y = animat[1];
			var x2 = animat[2];
			var y2 = animat[3];
			var ix1 = animto[0] - initcr[0];
			var iy1 = animto[1] - initcr[1];
			var ix2 = animto[2] - initcr[2];
			var iy2 = animto[3] - initcr[3];
			var pcent = 0;
			var velocity = options.swingSpeed;
			Selection.animMode(true);
			var animator = function() {
				return function() {
					pcent += (100 - pcent) / velocity;
					animat[0] = x + ((pcent / 100) * ix1);
					animat[1] = y + ((pcent / 100) * iy1);
					animat[2] = x2 + ((pcent / 100) * ix2);
					animat[3] = y2 + ((pcent / 100) * iy2);
					if (pcent < 100)
						animateStart();
					else
						Selection.done();
					if (pcent >= 99.8)
						pcent = 100;
					setSelectRaw(animat);
				};
			}();
			function animateStart() {
				window.setTimeout(animator, interv);
			}
			;
			animateStart();
		}
		;
		function setSelect(rect) {
			setSelectRaw( [ rect[0] / xscale, rect[1] / yscale,
					rect[2] / xscale, rect[3] / yscale ]);
		}
		;
		function setSelectRaw(l) {
			Coords.setPressed( [ l[0], l[1] ]);
			Coords.setCurrent( [ l[2], l[3] ]);
			Selection.update();
		}
		;
		function setOptions(opt) {
			if (typeof (opt) != 'object')
				opt = {};
			options = $.extend(options, opt);
			if (typeof (options.onChange) !== 'function')
				options.onChange = function() {
				};
			if (typeof (options.onSelect) !== 'function')
				options.onSelect = function() {
				};
		}
		;
		function tellSelect() {
			return unscale(Coords.getFixed());
		}
		;
		function tellScaled() {
			return Coords.getFixed();
		}
		;
		function setOptionsNew(opt) {
			setOptions(opt);
			interfaceUpdate();
		}
		;
		function disableCrop() {
			options.disabled = true;
			Selection.disableHandles();
			Selection.setCursor('default');
			Tracker.setCursor('default');
		}
		;
		function enableCrop() {
			options.disabled = false;
			interfaceUpdate();
		}
		;
		function cancelCrop() {
			Selection.done();
			Tracker.activateHandlers(null, null);
		}
		;
		function destroy() {
			$div.remove();
			$origimg.show();
		}
		;
		function interfaceUpdate(alt) {
			options.allowResize ? alt ? Selection.enableOnly() : Selection
					.enableHandles() : Selection.disableHandles();
			Tracker.setCursor(options.allowSelect ? 'crosshair' : 'default');
			Selection.setCursor(options.allowMove ? 'move' : 'default');
			$div.css('backgroundColor', options.bgColor);
			if ('setSelect' in options) {
				setSelect(opt.setSelect);
				Selection.done();
				delete (options.setSelect);
			}
			if ('trueSize' in options) {
				xscale = options.trueSize[0] / boundx;
				yscale = options.trueSize[1] / boundy;
			}
			xlimit = options.maxSize[0] || 0;
			ylimit = options.maxSize[1] || 0;
			xmin = options.minSize[0] || 0;
			ymin = options.minSize[1] || 0;
			if ('outerImage' in options) {
				$img.attr('src', options.outerImage);
				delete (options.outerImage);
			}
			Selection.refresh();
		}
		;
		$hdl_holder.hide();
		interfaceUpdate(true);
		var api = {
			animateTo :animateTo,
			setSelect :setSelect,
			setOptions :setOptionsNew,
			tellSelect :tellSelect,
			tellScaled :tellScaled,
			disable :disableCrop,
			enable :enableCrop,
			cancel :cancelCrop,
			focus :KeyManager.watchKeys,
			getBounds : function() {
				return [ boundx * xscale, boundy * yscale ];
			},
			getWidgetSize : function() {
				return [ boundx, boundy ];
			},
			release :Selection.release,
			destroy :destroy
		};
		$origimg.data('Jcrop', api);
		return api;
	};
	$.fn.Jcrop = function(options) {
		function attachWhenDone(from) {
			var loadsrc = options.useImg || from.src;
			var img = new Image();
			img.onload = function() {
				$.Jcrop(from, options);
			};
			img.src = loadsrc;
		}
		;
		if (typeof (options) !== 'object')
			options = {};
		this.each( function() {
			if ($(this).data('Jcrop')) {
				if (options == 'api')
					return $(this).data('Jcrop');
				else
					$(this).data('Jcrop').setOptions(options);
			} else
				attachWhenDone(this);
		});
		return this;
	};
})(jQuery);;
if (typeof pp_gemius_identifier != "undefined" && pp_gemius_identifier != "") {
	var pp_gemius_image = new Image();
	var pp_gemius_proto;
	if (document.location && document.location.protocol
			&& document.location.protocol == 'https:') {
		pp_gemius_proto = 'https:';
	} else {
		pp_gemius_proto = 'http:';
	}
	var pp_gemius_host = new String(pp_gemius_proto + '//gask.hit.gemius.pl/_'
			+ (new Date()).getTime());
	function gemius_load_script() {
		if (pp_gemius_image.width && pp_gemius_image.width > 1) {
			if (document.createElement) {
				var xp_body = document.body;
				var xp_javascript = document.createElement('script');
				var xp_url = pp_gemius_host + '/pp.js?id='
						+ pp_gemius_identifier;
				if (typeof (Error) != 'undefined') {
					eval("try { xp_javascript.src = xp_url; xp_javascript.type = 'text/javascript'; xp_javascript.defer = true; } catch(gemius_ex) { }")
					if (xp_body && xp_body.appendChild) {
						void (xp_body.appendChild(xp_javascript));
					}
				}
			}
		}
	}
	function gemius_parameters() {
		var d = document;
		var href = new String(d.location.href);
		var ref;
		if (d.referrer) {
			ref = new String(d.referrer);
		} else {
			ref = "";
		}
		var t = typeof Error;
		if (t != 'undefined') {
			eval("try { if (typeof(top.document.referrer)=='string') { ref = top.document.referrer } } catch(gemius_ex) { }")
		}
		var url = '&tz=' + (new Date()).getTimezoneOffset() + '&href='
				+ escape(href.substring(0, 299)) + '&ref='
				+ escape(ref.substring(0, 299));
		if (screen) {
			var s = screen;
			if (s.width)
				url += '&screen=' + s.width + 'x' + s.height;
			if (s.colorDepth)
				url += '&col=' + s.colorDepth;
		}
		return url;
	}
	if (window.attachEvent) {
		window.attachEvent("onload", gemius_load_script);
	} else if (window.addEventListener) {
		window.addEventListener("load", gemius_load_script, false);
	}
	var pp_gemius_url = pp_gemius_host + '/rexdot.gif?l=11&id=';
	pp_gemius_url += pp_gemius_identifier;
	pp_gemius_url += gemius_parameters();
	pp_gemius_image.src = pp_gemius_url;
}