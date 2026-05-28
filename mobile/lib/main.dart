import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import 'providers/auth_provider.dart' as app_auth;
import 'screens/login_screen.dart';
import 'screens/home_screen.dart';
import 'screens/splash_screen.dart';
import 'screens/network_detail_screen.dart';
import 'screens/listing_detail_screen.dart';
import 'screens/chat_screen.dart';

void main() {
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => app_auth.AuthProvider()),
      ],
      child: const MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<app_auth.AuthProvider>();

    final GoRouter router = GoRouter(
      initialLocation: '/splash',
      routes: [
        GoRoute(
          path: '/splash',
          builder: (context, state) => const SplashScreen(),
        ),
        GoRoute(
          path: '/login',
          builder: (context, state) => const LoginScreen(),
        ),
        GoRoute(
          path: '/',
          builder: (context, state) => const HomeScreen(),
          redirect: (context, state) {
            if (!authProvider.isAuthenticated && state.uri.path != '/login') return '/login';
            return null;
          },
        ),
        GoRoute(
          path: '/network/:id',
          builder: (context, state) => NetworkDetailScreen(id: int.parse(state.pathParameters['id']!)),
        ),
        GoRoute(
          path: '/listing/:id',
          builder: (context, state) => ListingDetailScreen(id: int.parse(state.pathParameters['id']!)),
        ),
        GoRoute(
          path: '/chat/:userId',
          builder: (context, state) => ChatScreen(userId: int.parse(state.pathParameters['userId']!)),
        ),
      ],
    );

    return MaterialApp.router(
      title: 'Swap Net',
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
          seedColor: Colors.teal,
          brightness: Brightness.light,
        ),
      ),
      darkTheme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
          seedColor: Colors.teal,
          brightness: Brightness.dark,
        ),
      ),
      routerConfig: router,
    );
  }
}
