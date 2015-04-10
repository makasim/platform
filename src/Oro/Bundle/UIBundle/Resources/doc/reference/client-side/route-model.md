<a name="module_RouteModel"></a>
## RouteModel
Abstraction of route

**Augment**: BaseModel  

* [RouteModel](#module_RouteModel)
  * [.defaults](#module_RouteModel#defaults) : <code>Object</code>
  * [.routeName](#module_RouteModel#routeName) : <code>string</code>
  * [.routeQueryParameterNames](#module_RouteModel#routeQueryParameterNames) : <code>Array.&lt;string&gt;</code>
  * [.getAcceptableParameters()](#module_RouteModel#getAcceptableParameters) ⇒ <code>Array.&lt;string&gt;</code>
  * [.getUrl([parameters])](#module_RouteModel#getUrl) ⇒ <code>string</code>

<a name="module_RouteModel#defaults"></a>
### routeModel.defaults : <code>Object</code>
**Kind**: instance property of <code>[RouteModel](#module_RouteModel)</code>  
<a name="module_RouteModel#routeName"></a>
### routeModel.routeName : <code>string</code>
Name of the route

**Kind**: instance property of <code>[RouteModel](#module_RouteModel)</code>  
<a name="module_RouteModel#routeQueryParameterNames"></a>
### routeModel.routeQueryParameterNames : <code>Array.&lt;string&gt;</code>
List of acceptable query parameter names for this route

**Kind**: instance property of <code>[RouteModel](#module_RouteModel)</code>  
<a name="module_RouteModel#getAcceptableParameters"></a>
### routeModel.getAcceptableParameters() ⇒ <code>Array.&lt;string&gt;</code>
Return list of parameter names accepted by this route.

**Kind**: instance method of <code>[RouteModel](#module_RouteModel)</code>  
<a name="module_RouteModel#getUrl"></a>
### routeModel.getUrl([parameters]) ⇒ <code>string</code>
Returns url defined by this model

**Kind**: instance method of <code>[RouteModel](#module_RouteModel)</code>  
**Returns**: <code>string</code> - route url  

| Param | Type | Description |
| --- | --- | --- |
| [parameters] | <code>Object</code> | parameters to override |
