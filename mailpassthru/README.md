#Application Object Conversation Email Pass Through
This POC exists to demonstrate using Sendgrid as both an egress and ingress 
email agent for conversations associated to objects and users in an application,
specifically First Freight.

###Process
The process is as follows:

 1. Reply to a conversation.
 2. Create a GUID by md5'ing data and a nonce as a security token.
 3. Use GUID as key to store data in cache for retrieval on reply.
 4. Have Sendgrid send out an email with a reply-to in the format of "GUID.nonce@responder.firstfreight.com".
 5. On email reply from recipient, Sendgrid web hook will trigger a request.
 6. On response to a request, we'll unpack the data using the GUID in the reply-to and validate with the nonce.
 7. Upon verification, save the reply message and data to the conversation chain.
 
 
