import 'dart:convert';
import '../models/models.dart';
import 'api_service.dart';

class NetworkService {
  final ApiService _api = ApiService();

  Future<List<Network>> getNetworks() async {
    final response = await _api.get('/networks');
    if (response.statusCode == 200) {
      List data = jsonDecode(response.body);
      return data.map((e) => Network.fromJson(e)).toList();
    }
    return [];
  }

  Future<Network?> getNetworkDetails(int id) async {
    final response = await _api.get('/networks/$id');
    if (response.statusCode == 200) {
      return Network.fromJson(jsonDecode(response.body));
    }
    return null;
  }

  Future<bool> joinNetwork(String qrCode) async {
    final response = await _api.post('/networks/join', {'qr_code': qrCode});
    return response.statusCode == 200;
  }
}

class ListingService {
  final ApiService _api = ApiService();

  Future<List<Listing>> getListings() async {
    final response = await _api.get('/listings');
    if (response.statusCode == 200) {
      List data = jsonDecode(response.body);
      return data.map((e) => Listing.fromJson(e)).toList();
    }
    return [];
  }

  Future<bool> createListing(Map<String, String> data, List<String> imagePaths) async {
    // Implementation for multipart would go here in a full app
    return true; 
  }
}

class MessageService {
  final ApiService _api = ApiService();

  Future<List<Message>> getMessages(int userId) async {
    final response = await _api.get('/messages/$userId');
    if (response.statusCode == 200) {
      List data = jsonDecode(response.body);
      return data.map((e) => Message.fromJson(e)).toList();
    }
    return [];
  }

  Future<bool> sendMessage(int receiverId, String content) async {
    final response = await _api.post('/messages', {
      'receiver_id': receiverId,
      'content': content,
    });
    return response.statusCode == 201;
  }
}
