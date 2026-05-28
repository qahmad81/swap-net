class User {
  final int id;
  final String name;
  final String email;
  final String? avatar;
  final String? deviceToken;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.avatar,
    this.deviceToken,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      avatar: json['avatar'],
      deviceToken: json['device_token'],
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'email': email,
    'avatar': avatar,
    'device_token': deviceToken,
  };
}

class Network {
  final int id;
  final String name;
  final String? description;
  final String? qrCode;
  final List<User>? members;

  Network({
    required this.id,
    required this.name,
    this.description,
    this.qrCode,
    this.members,
  });

  factory Network.fromJson(Map<String, dynamic> json) {
    return Network(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      qrCode: json['qr_code'],
      members: json['members'] != null
          ? (json['members'] as List).map((i) => User.fromJson(i)).toList()
          : null,
    );
  }
}

class Listing {
  final int id;
  final int userId;
  final String title;
  final String description;
  final String category;
  final List<String> images;
  final User? user;

  Listing({
    required this.id,
    required this.userId,
    required this.title,
    required this.description,
    required this.category,
    required this.images,
    this.user,
  });

  factory Listing.fromJson(Map<String, dynamic> json) {
    return Listing(
      id: json['id'],
      userId: json['user_id'],
      title: json['title'],
      description: json['description'],
      category: json['category'],
      images: List<String>.from(json['images'] ?? []),
      user: json['user'] != null ? User.fromJson(json['user']) : null,
    );
  }
}

class Offer {
  final int id;
  final int listingId;
  final int userId;
  final String status;
  final String? message;
  final Listing? listing;
  final User? user;

  Offer({
    required this.id,
    required this.listingId,
    required this.userId,
    required this.status,
    this.message,
    this.listing,
    this.user,
  });

  factory Offer.fromJson(Map<String, dynamic> json) {
    return Offer(
      id: json['id'],
      listingId: json['listing_id'],
      userId: json['user_id'],
      status: json['status'],
      message: json['message'],
      listing: json['listing'] != null ? Listing.fromJson(json['listing']) : null,
      user: json['user'] != null ? User.fromJson(json['user']) : null,
    );
  }
}

class Message {
  final int id;
  final int senderId;
  final int receiverId;
  final String content;
  final DateTime createdAt;

  Message({
    required this.id,
    required this.senderId,
    required this.receiverId,
    required this.content,
    required this.createdAt,
  });

  factory Message.fromJson(Map<String, dynamic> json) {
    return Message(
      id: json['id'],
      senderId: json['sender_id'],
      receiverId: json['receiver_id'],
      content: json['content'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}

class DeliveryRequest {
  final int id;
  final int offerId;
  final String status;
  final String? trackingNumber;

  DeliveryRequest({
    required this.id,
    required this.offerId,
    required this.status,
    this.trackingNumber,
  });

  factory DeliveryRequest.fromJson(Map<String, dynamic> json) {
    return DeliveryRequest(
      id: json['id'],
      offerId: json['offer_id'],
      status: json['status'],
      trackingNumber: json['tracking_number'],
    );
  }
}

class Review {
  final int id;
  final int reviewerId;
  final int revieweeId;
  final int rating;
  final String? comment;

  Review({
    required this.id,
    required this.reviewerId,
    required this.revieweeId,
    required this.rating,
    this.comment,
  });

  factory Review.fromJson(Map<String, dynamic> json) {
    return Review(
      id: json['id'],
      reviewerId: json['reviewer_id'],
      revieweeId: json['reviewee_id'],
      rating: json['rating'],
      comment: json['comment'],
    );
  }
}
