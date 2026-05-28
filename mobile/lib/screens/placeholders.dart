import 'package:flutter/material.dart';

class NetworkListScreen extends StatelessWidget {
  const NetworkListScreen({super.key});
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('My Networks')),
      body: const Center(child: Text('Networks coming soon')),
    );
  }
}

class ConversationListScreen extends StatelessWidget {
  const ConversationListScreen({super.key});
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Messages')),
      body: const Center(child: Text('Messages coming soon')),
    );
  }
}

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Profile')),
      body: const Center(child: Text('Profile coming soon')),
    );
  }
}

class NetworkDetailScreen extends StatelessWidget {
  final int id;
  const NetworkDetailScreen({super.key, required this.id});
  @override
  Widget build(BuildContext context) {
    return Scaffold(appBar: AppBar(title: Text('Network $id')));
  }
}

class ListingDetailScreen extends StatelessWidget {
  final int id;
  const ListingDetailScreen({super.key, required this.id});
  @override
  Widget build(BuildContext context) {
    return Scaffold(appBar: AppBar(title: Text('Listing $id')));
  }
}

class ChatScreen extends StatelessWidget {
  final int userId;
  const ChatScreen({super.key, required this.userId});
  @override
  Widget build(BuildContext context) {
    return Scaffold(appBar: AppBar(title: Text('Chat with $userId')));
  }
}
