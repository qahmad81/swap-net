import 'package:flutter/material.dart';
import '../models/models.dart';
import '../services/data_services.dart';

class ListingFeedScreen extends StatefulWidget {
  const ListingFeedScreen({super.key});

  @override
  State<ListingFeedScreen> createState() => _ListingFeedScreenState();
}

class _ListingFeedScreenState extends State<ListingFeedScreen> {
  final ListingService _service = ListingService();
  List<Listing> _listings = [];

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    final listings = await _service.getListings();
    setState(() => _listings = listings);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Swap Net')),
      body: GridView.builder(
        padding: const EdgeInsets.all(8),
        gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: 2,
          childAspectRatio: 0.8,
        ),
        itemCount: _listings.length,
        itemBuilder: (context, index) => Card(
          child: Column(
            children: [
              Expanded(child: Container(color: Colors.grey[300])),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: Text(_listings[index].title),
              ),
            ],
          ),
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {},
        child: const Icon(Icons.add),
      ),
    );
  }
}
