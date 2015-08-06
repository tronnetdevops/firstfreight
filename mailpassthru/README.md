#Application Object Conversation Email Pass Through
This POC exists to demonstrate using Sendgrid as both an egress and ingress 
email agent for conversations associated to objects and users in an application,
specifically First Freight.

###Workflow
The process is as follows:

 1. Reply to a conversation.
 2. Create a GUID by md5'ing data and a nonce as a security token.
 3. Use GUID as key to store data in cache for retrieval on reply.
 4. Have Sendgrid send out an email with a reply-to in the format of "GUID.nonce@responder.firstfreight.com".
 5. On email reply from recipient, Sendgrid web hook will trigger a request.
 6. On response to a request, we'll unpack the data using the GUID in the reply-to and validate with the nonce.
 7. Upon verification, save the reply message and data to the conversation chain.
 
 
###Files Explained

```
convo.php
```
This is the primary entry point. It will display the UI for conversation chains
and a form to submit replies.

```
mailer.php
```
This is where the form in `convo.php` points too. 

It will gather information from the form fields and create a data package. This
package will be stored in the cache and Sendgrid will be utilized to send an
email to all participents in the conversation.

```
mailhook.php
```
This is the file that will be triggered by Sedngrid Web Hook on reply to a message
via email. It will validate the nonce and then add the reply message to the 
database.

----

```
connectors/
```
These files are responsible for instantiating handlers to their respective service,
which currently are:

 * MySQL
 * Sendgrid
 * Memcached
 
```
data/
```
This is where config files and DB setup files exist.