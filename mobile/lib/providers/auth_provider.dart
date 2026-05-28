import 'package:flutter/material.dart';
import '../services/auth_service.dart';
import '../models/models.dart';

class AuthProvider extends ChangeNotifier {
  final AuthService _authService = AuthService();
  User? _user;
  bool _isLoading = false;

  User? get user => _user;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _user != null;

  Future<void> loginWithGoogle() async {
    _isLoading = true;
    notifyListeners();
    _user = await _authService.loginWithGoogle();
    _isLoading = false;
    notifyListeners();
  }

  Future<void> logout() async {
    await _authService.logout();
    _user = null;
    notifyListeners();
  }
}
